import { newSpecPage } from '@stencil/core/testing';
import { ApieSkeletonForm } from '../apie-skeleton-form';

describe('apie-skeleton-form', () => {
  it('renders', async () => {
    const page = await newSpecPage({
      components: [ApieSkeletonForm],
      html: `<apie-skeleton-form></apie-skeleton-form>`,
    });
    expect(page.root).toEqualHtml(`
      <apie-skeleton-form>
        <mock:shadow-root>
          <slot></slot>
        </mock:shadow-root>
      </apie-skeleton-form>
    `);
  });
});
