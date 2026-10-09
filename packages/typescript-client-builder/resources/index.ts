import { BoundedContextDefinition, createBoundedContext, EntityConstructor, type BoundedContext, type Entity, type EntityForm } from "./build-script";
import { FetchTransportLayer, InMemoryTransportLayer } from "./transport-layer";

export interface CreateEntityDefinition {
    type: 'createEntity',
    name: string
}

export interface CreatePropertyDefinition {
    type: 'createProperty',
    entityName: string,
    propertyName: string,
    writableOnCreation: boolean,
    writableOnModification: boolean,
    readable: boolean;
}

type ResourceDefinition = CreateEntityDefinition|CreatePropertyDefinition;

type ResourceDefinitionList = Array<ResourceDefinition>

interface ResourcesDefinition {
    [name: string]: ResourceDefinitionList;
}

function applyDefinition(context: BoundedContextDefinition, definition: ResourceDefinition): void {
    switch (definition.type) {
        case 'createEntity':
            context.createEntity(definition.name);
            break;
        case 'createProperty':
            context.createProperty(
                definition.entityName,
                definition.propertyName,
                definition.writableOnCreation,
                definition.writableOnModification,
                definition.readable
            );
            break;
    }
}

export function createForApi(apiUrl: string, definition: ResourcesDefinition): Record<string, BoundedContext> {
    const transportLayer = new FetchTransportLayer(apiUrl);
    const result: Record<string, BoundedContext> = {};
    for (let [key, data] of Object.entries(definition)) {
        const definition =  createBoundedContext(
            key,
            transportLayer
        );
        for (let methodDefinition of data) {
            applyDefinition(definition, methodDefinition);
        }

        result[key] = definition.toBoundedContext()
    }
    return result;
}

export function createForTest(definition: ResourcesDefinition): Record<string, BoundedContext> {
    const transportLayer = new InMemoryTransportLayer();
    const result: Record<string, BoundedContext> = {};
    for (let [key, data] of Object.entries(definition)) {
        const definition =  createBoundedContext(
            key,
            transportLayer
        );
        for (let methodDefinition of data) {
            applyDefinition(definition, methodDefinition);
        }

        result[key] = definition.toBoundedContext()
    }
    return result;
}

export class BrowserPlayground {
  private root: HTMLElement | null = null;

  private selectedContext: string = '';
  private selectedEntity: string = '';

  private activeEntity: Entity | null = null;
  private activeEntityContext: BoundedContext | null = null;
  private activeForm: EntityForm | null = null;

  private logs: Array<{ time: string; message: string; value: unknown }> = [];

  constructor(private definition: ResourcesDefinition, private api: Record<string, BoundedContext>) {
    this.selectedContext = Object.keys(definition)[0] ?? '';
    this.selectedEntity = this.entities(this.selectedContext)[0] ?? '';
  }

  public start(id: string): BrowserPlayground {
    const element = document.getElementById(id);

    if (!element) {
      throw new Error(
        `Cannot start Apie browser playground: element with id "${id}" was not found`
      );
    }

    this.root = element;
    this.reset();

    return this;
  }

   private defs(contextName: string, entityName: string): Array<CreatePropertyDefinition> {
    return (this.definition[contextName] || []).filter(
      (x): x is CreatePropertyDefinition =>
        x.type === "createProperty" && x.entityName === entityName
    );
  }

  private entities(contextName: string): string[] {
    return (this.definition[contextName] || [])
      .filter(x => x.type === "createEntity")
      .map(x => x.name);
  }

  private context(): BoundedContext {
    return this.api[this.selectedContext];
  }

  Ctor(): EntityConstructor {
    return this.context().entities[this.selectedEntity];
  }

  private fmt(value: unknown): string {
    try {
      return JSON.stringify(
        value,
        (_, x) =>
          typeof x === "bigint" ? `${x}n` : x,
        2
      );
    } catch {
      return String(value);
    }
  }

  private esc(value: unknown) {
    return String(value ?? "").replace(
      /[&<>"']/g,
      c =>
        ({
          "&": "&amp;",
          "<": "&lt;",
          ">": "&gt;",
          '"': "&quot;",
          "'": "&#39;"
        } as Record<string, string>)[c] ?? c
    );
  }

  private log(message: string, value?: unknown) {
    this.logs.unshift({
      time: new Date().toLocaleTimeString(),
      message,
      value
    });

    this.renderLog();
  }

  private errorMessage(error: unknown): string {
    return error instanceof Error ? error.message : String(error);
  }

  private rootElement(): HTMLElement {
    if (!this.root) {
      throw new Error("Browser playground has not been started");
    }
    return this.root;
  }

  private showTestOutput(text: string): void {
    const output = this.rootElement().querySelector("#test-output");
    if (output) output.textContent = text;
  }

  reset() {
     this.selectedContext =
      Object.keys(this.definition)[0] ?? "";

    this.selectedEntity =
      this.entities(this.selectedContext)[0] ?? '';

    this.activeEntity = null;
    this.activeEntityContext = null;
    this.activeForm = null;
    this.logs = [];

    if (this.root) {
      this.render();
      this.log("Test environment reset");
    }
  }

  render() {
    if (!this.root) {
      return;
    }

    const contextNames = Object.keys(this.definition);

    this.root.innerHTML = `
      <header>
        <div>
          <h1>Apie Test Playground</h1>
          <p>Deterministic in-memory CRUD and state-transition tester.</p>
        </div>
        <button id="reset" class="primary">Reset</button>
      </header>

      <div class="layout">
        <aside class="panel sidebar">
          <h2>Bounded contexts</h2>

          <div class="tabs">
            ${contextNames
              .map(
                c => `
                  <button
                    class="${c === this.selectedContext ? "active" : ""}"
                    data-context="${this.esc(c)}"
                  >
                    ${this.esc(c)}
                  </button>
                `
              )
              .join("")}
          </div>

          <h2>Entities</h2>

          <div class="entity-list">
            ${this.entities(this.selectedContext)
              .map(
                e => `
                  <button
                    class="${e === this.selectedEntity ? "active" : ""}"
                    data-entity="${this.esc(e)}"
                  >
                    ${this.esc(e)}
                  </button>
                `
              )
              .join("")}
          </div>
        </aside>

        <section class="content">
          <div class="panel">
            <div class="panel-head">
              <div>
                <h2>${this.esc(this.selectedEntity)}</h2>
                <span class="muted">
                  ${this.esc(this.selectedContext)}
                </span>
              </div>
            </div>

            <div class="grid">
              <div>
                <h3>Create</h3>

                <div id="create-fields">
                  ${this.createFields()}
                </div>

                <button id="create" class="primary">
                  Create & Persist
                </button>
              </div>

              <div>
                <h3>Active entity</h3>
                ${this.entityPanel()}
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-head">
              <h2>Tests</h2>
            </div>

            <div class="test-buttons">
              <button id="test-rules">Property rules</button>
              <button id="test-lifecycle">Full lifecycle</button>
              <button id="test-delete">Delete lifecycle</button>
            </div>

            <pre id="test-output" class="output">Run a test suite.</pre>
          </div>

          <div class="panel">
            <div class="panel-head">
              <h2>Activity</h2>
              <button id="clear-log">Clear</button>
            </div>

            <pre id="log" class="output"></pre>
          </div>
        </section>
      </div>
    `;

    this.bindEvents();
    this.renderLog();
  }

  bindEvents() {
    const root = this.rootElement();

    root
      .querySelector("#reset")
      ?.addEventListener("click", () => {
        this.reset();
      });

    root
      .querySelectorAll<HTMLButtonElement>("[data-context]")
      .forEach(button => {
        button.addEventListener("click", () => {
          this.selectedContext = button.dataset.context ?? "";
          this.selectedEntity =
            this.entities(this.selectedContext)[0] ?? '';

          this.activeEntity = null;
          this.activeForm = null;

          this.render();
        });
      });

    root
      .querySelectorAll<HTMLButtonElement>("[data-entity]")
      .forEach(button => {
        button.addEventListener("click", () => {
          this.selectedEntity = button.dataset.entity ?? "";

          this.activeEntity = null;
          this.activeForm = null;

          this.render();
        });
      });

    root
      .querySelector("#create")
      ?.addEventListener("click", () => {
        this.createEntity();
      });

    root
      .querySelector("#test-rules")
      ?.addEventListener("click", () => {
        this.propertyRulesTest();
      });

    root
      .querySelector("#test-lifecycle")
      ?.addEventListener("click", () => {
        this.lifecycleTest();
      });

    root
      .querySelector("#test-delete")
      ?.addEventListener("click", () => {
        this.deleteTest();
      });

    root
      .querySelector("#clear-log")
      ?.addEventListener("click", () => {
        this.logs = [];
        this.renderLog();
      });

    this.bindEntityActions();
  }

  createFields() {
    return this.defs(
      this.selectedContext,
      this.selectedEntity
    )
      .map(
        d => `
          <label>
            ${this.esc(d.propertyName)}

            <span class="flags">
              ${d.writableOnCreation ? "W" : "—"} /
              ${d.readable ? "R" : "—"}
            </span>

            <input
              data-create="${this.esc(d.propertyName)}"
              ${d.writableOnCreation ? "" : "disabled"}
              placeholder="${
                d.writableOnCreation
                  ? "writable on creation"
                  : "read-only on creation"
              }"
            >
          </label>
        `
      )
      .join("");
  }

  entityPanel() {
    if (!this.activeEntity) {
      return `
        <div class="empty">
          No entity yet. Create one to inspect its state
          and modification form.
        </div>
      `;
    }
    const activeEntity = this.activeEntity;

    const props = this.defs(
      this.selectedContext,
      this.selectedEntity
    );

    return `
      <div class="state">
        State:
        <strong>${this.activeEntityContext ? "Persisted" : "Draft"}</strong>
        · id:
        <strong>${this.esc(activeEntity.id)}</strong>
      </div>

      <div class="property-list">
        ${props
          .map(d => {
            let value;

            try {
              value = activeEntity[d.propertyName];
            } catch (error) {
              value = `[${this.errorMessage(error)}]`;
            }

            return `
              <div>
                <span>${this.esc(d.propertyName)}</span>
                <code>
                  ${this.esc(
                    value === undefined
                      ? "undefined"
                      : String(value)
                  )}
                </code>
              </div>
            `;
          })
          .join("")}
      </div>

      <div class="actions">
        <button id="form">
          Create modification form
        </button>

        <button id="persist" class="primary">
          Persist
        </button>

        <button id="delete">
          Delete
        </button>
      </div>

      ${this.activeForm ? this.formPanel() : ""}
    `;
  }

  formPanel() {
    return `
      <div class="form-box">
        <h3>Modification form</h3>

        ${this.defs(
          this.selectedContext,
          this.selectedEntity
        )
          .map(
            d => `
              <label>
                ${this.esc(d.propertyName)}

                <span class="flags">
                  ${d.writableOnModification ? "W" : "—"} /
                  ${d.readable ? "R" : "—"}
                </span>

                <input
                  data-form="${this.esc(d.propertyName)}"
                  ${d.writableOnModification ? "" : "disabled"}
                  placeholder="${
                    d.writableOnModification
                      ? "writable on modification"
                      : "not writable on modification"
                  }"
                >
              </label>
            `
          )
          .join("")}

        <button id="save-form" class="primary">
          Apply form & persist
        </button>
      </div>
    `;
  }

  bindEntityActions() {
    const root = this.rootElement();

    root
      .querySelector("#form")
      ?.addEventListener("click", () => {
        try {
          if (!this.activeEntity) return;
          this.activeForm = this.activeEntity.createForm();

          this.render();
          this.log("Created modification form");
        } catch (error) {
          this.log("Create form failed", this.errorMessage(error));
        }
      });

    root
      .querySelector("#persist")
      ?.addEventListener("click", async () => {
        try {
          if (!this.activeEntity) return;
          const entity = this.activeEntity;
          await this.context().persist(entity);

          this.render();
          this.log(
            "Entity persisted",
            this.snapshot(entity)
          );
        } catch (error) {
          this.log("Persist failed", this.errorMessage(error));
        }
      });

    root
      .querySelector("#delete")
      ?.addEventListener("click", async () => {
        try {
          if (!this.activeEntity) return;
          await this.context().delete(this.activeEntity);

          this.log("Entity deleted");

          this.activeEntity = null;
          this.activeForm = null;

          this.render();
        } catch (error) {
          this.log("Delete failed", this.errorMessage(error));
        }
      });

    root
      .querySelector("#save-form")
      ?.addEventListener("click", async () => {
        for (const d of this.defs(
          this.selectedContext,
          this.selectedEntity
        )) {
          const selector =
            `[data-form="${CSS.escape(
              d.propertyName
            )}"]`;

          const element = root.querySelector<HTMLInputElement>(selector);

          if (
            element &&
            !element.disabled &&
            element.value !== ""
          ) {
            try {
              if (!this.activeForm) return;
              this.activeForm[d.propertyName] =
                this.parseValue(element.value);
            } catch (error) {
              this.log(
                `Setting ${d.propertyName} failed`,
                this.errorMessage(error)
              );
            }
          }
        }

        try {
          if (!this.activeForm) return;
          const form = this.activeForm;
          await this.context().persist(form);
          this.activeForm = null;

          this.render();

          this.log(
            "Modification persisted",
            this.snapshot(form)
          );
        } catch (error) {
          this.log(
            "Modification failed",
            this.errorMessage(error)
          );
        }
      });
  }

  parseValue(value: string): unknown {
    const text = value.trim();

    if (text === "true") return true;
    if (text === "false") return false;
    if (text === "null") return null;

    if (/^-?\d+(?:\.\d+)?$/.test(text)) {
      return Number(text);
    }

    try {
      return JSON.parse(text);
    } catch {
      return value;
    }
  }

  async createEntity() {
    try {
      const data: Record<string, unknown> = {};

      for (const d of this.defs(
        this.selectedContext,
        this.selectedEntity
      )) {
        const element = this.rootElement().querySelector<HTMLInputElement>(
          `[data-create="${CSS.escape(
            d.propertyName
          )}"]`
        );

        if (
          element &&
          !element.disabled &&
          element.value !== ""
        ) {
          data[d.propertyName] =
            this.parseValue(element.value);
        }
      }

      const C = this.Ctor();

      const entity = new C(data);
      this.activeEntity = entity;

      await this.context().persist(entity);

      this.activeEntityContext =
        this.context();

      this.render();

      this.log(
        `Created and persisted ${this.selectedEntity}`,
        this.snapshot(entity)
      );
    } catch (error) {
      this.log("Create failed", this.errorMessage(error));
    }
  }

  snapshot(entity: Entity | EntityForm): Record<string, unknown> {
    const result: Record<string, unknown> = {};

    for (const d of this.defs(
      this.selectedContext,
      this.selectedEntity
    )) {
      try {
        result[d.propertyName] =
          entity[d.propertyName];
      } catch (error) {
        result[d.propertyName] =
          `[${this.errorMessage(error)}]`;
      }
    }

    return result;
  }

  async propertyRulesTest() {
    const out: string[] = [];
    let passed = 0;
    let failed = 0;

    const test = (name: string, fn: () => void) => {
      try {
        fn();
        out.push(`✓ ${name}`);
        passed++;
      } catch (error) {
        out.push(`✗ ${name}: ${this.errorMessage(error)}`);
        failed++;
      }
    };

    const C = this.Ctor();
    const entity = new C({});

    const propertyDefinitions: Record<string, CreatePropertyDefinition> = {};
    for (const definition of this.defs(
      this.selectedContext,
      this.selectedEntity
    )) {
      propertyDefinitions[definition.propertyName] = definition;
    }

    for (const [name, definition] of Object.entries(
      propertyDefinitions
    )) {
      if (!definition.writableOnCreation) {
        test(
          `${name} rejects creation write`,
          () => {
            entity[name] = "should fail";
          }
        );
      }
    }

    for (const [name, definition] of Object.entries(
      propertyDefinitions
    )) {
      if (!definition.readable) {
        test(
          `${name} rejects read`,
          () => {
            const value = entity[name];

            if (!String(value).startsWith("[")) {
              throw new Error(
                "property unexpectedly readable"
              );
            }
          }
        );
      }
    }

    const element = this.rootElement().querySelector("#test-output");
    if (element) {
      element.textContent =
        out.join("\n") +
        `\n\n${passed} passed, ${failed} failed`;
    }
  }

  async lifecycleTest() {
    const out: string[] = [];

    try {
      const C = this.Ctor();
      const entity = new C({});

      out.push(
        `Draft: created ${this.selectedEntity}`
      );

      await this.context().persist(entity);

      out.push(`Persisted: id=${entity.id}`);

      const form = entity.createForm();

      const writable = this.defs(
        this.selectedContext,
        this.selectedEntity
      ).find(
        d => d.writableOnModification
      );

      if (writable) {
        form[writable.propertyName] =
          this.parseValue("playground-test");

        out.push(
          `Modification: changed ${writable.propertyName}`
        );

        await this.context().persist(form);

        out.push(
          "Updated: persisted modification"
        );
      } else {
        out.push(
          "Modification: no writable-on-modification property defined"
        );
      }

      this.showTestOutput(
        "✓ Lifecycle passed\n\n" +
        out.join("\n")
      );

      this.log(
        "Lifecycle test passed",
        out
      );
    } catch (error) {
      this.showTestOutput(
        "✗ Lifecycle failed\n\n" +
        out.join("\n") +
        "\n" +
        this.errorMessage(error)
      );

      this.log(
        "Lifecycle test failed",
        this.errorMessage(error)
      );
    }
  }

  async deleteTest() {
    try {
      const C = this.Ctor();
      const entity = new C({});

      await this.context().persist(entity);
      await this.context().delete(entity);

      let failedAsExpected = false;

      try {
        await this.context().delete(entity);
      } catch {
        failedAsExpected = true;
      }

      if (!failedAsExpected) {
        throw new Error(
          "Second delete unexpectedly succeeded"
        );
      }

      this.showTestOutput(
        "✓ Delete lifecycle passed\n\n" +
        "Create → Persist → Delete → Delete rejected"
      );

      this.log("Delete lifecycle passed");
    } catch (error) {
      this.showTestOutput(
        "✗ Delete lifecycle failed\n\n" +
        this.errorMessage(error)
      );

      this.log(
        "Delete lifecycle failed",
        this.errorMessage(error)
      );
    }
  }

  renderLog() {
    if (!this.root) {
      return;
    }

    const element =
      this.root.querySelector("#log");

    if (!element) {
      return;
    }

    element.textContent = this.logs
      .map(
        x =>
          `[${x.time}] ${x.message}` +
          (x.value === undefined
            ? ""
            : `\n${this.fmt(x.value)}`)
      )
      .join("\n\n");
  }
}

export function createBrowserPlayground(definition: ResourcesDefinition): BrowserPlayground {
  const api = createForTest(definition);

  return new BrowserPlayground(definition, api);
}