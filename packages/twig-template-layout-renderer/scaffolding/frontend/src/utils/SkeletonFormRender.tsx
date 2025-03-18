/*import { h, VNode }  from '@stencil/core';*/
/*import { InputState, FallbackRenderInfo, RenderInfo, toString, toArray, toFileList, toEmptyFileList, FormGroupState, FormListRowState, FormListRowAddState, SubmitButtonState, createErrorMessage } from 'apie-form-elements';*/
import { RenderInfo, FallbackRenderInfo } from 'apie-form-elements';

export class SkeletonFormRender extends RenderInfo
{
    constructor(
    ) {
        super(new FallbackRenderInfo());
        this.singleInputRenderers = {
            /*"date-display"(state: InputState) {
                return renderIonInput(state, 'text', <ion-icon slot="end" icon="calendar-outline"></ion-icon>)
            },
            "date-hours"(state: InputState) {
                return renderIonInput(state, 'number', [], { placeholder: 'HH', label: 'Hours', style: {"--padding-top": '4px' } })
            },
            "date-minutes"(state: InputState) {
                return renderIonInput(state, 'number', [], { placeholder: 'MM', label: 'Minutes', style: {"--padding-top": '4px' } })
            },
            "date-seconds"(state: InputState) {
                return renderIonInput(state, 'number', [], { placeholder: 'SS', label: 'Seconds', style: {"--padding-top": '4px' } })
            },
            "date-milliseconds"(state: InputState) {
                return renderIonInput(state, 'number', [], { placeholder: 'Ms', label: 'Milliseconds', style: {"--padding-top": '4px' } })
            },
            "date-microseconds"(state: InputState) {
                return renderIonInput(state, 'number', [], { placeholder: '000000', label: 'Microseconds', style: {"--padding-top": '4px' } })
            },
            "date-date"(state: InputState) {
                return renderIonInput(state, 'number', [], { placeholder: 'DD', label: 'Date', style: {"--padding-top": '4px' } })
            },
            "date-month"(state: InputState) {
                return renderIonInput(state, 'number', [], { placeholder: 'MM', label: 'Month', style: {"--padding-top": '4px' } })
            },
            "date-year"(state: InputState) {
                return renderIonInput(state, 'number', [], { placeholder: 'YYYY', label: 'Year', style: {"--padding-top": '4px' } })
            },
            text(state: InputState) {
              return renderIonInput(state, 'text');
            },
            number(state: InputState) {
                return renderIonInput(state, 'number');
            },
            integer(state: InputState) {
                return renderIonInput(state, 'number');
            },
            password(state: InputState) {
                return renderIonInput(state, 'password', state.value && <ion-input-password-toggle slot="end"></ion-input-password-toggle>)
            },
            textarea(state: InputState) {
                return <ion-textarea
                    label={state.label}
                    label-placement="floating"
                    fill="outline" 
                    auto-grow="true"
                    disabled={state.disabled}
                    onIonInput={(ev: any) => state.valueChanged(ev.target?.value)}
                    name={state.name}
                    value={toString(state.value)}></ion-textarea>;
            },
            file(state: InputState) {
                return (
                  <div style={flexBasis} onClick={(ev) => {ev.stopImmediatePropagation(); openFileDialog(state.valueChanged)}}>
                    <input type="file" style={ { display: 'none'} } disabled={state.disabled} onInput={(ev: any) => state.valueChanged(ev.target?.files[0])} name={state.name} files={state.value ? toFileList(state.value) : toEmptyFileList()}/>
                    <ion-input
                        style={ (state.value as any)?.name ? {"--padding-top": '4px'} : { "--padding-top": '4px', 'fontStyle': 'italic' }}
                        label={state.label}
                        label-placement={ state.value ? "floating" : 'stacked' }
                        fill="outline"
                        type="text"
                        placeholder="no file selected"
                        value={ state.value ? (state.value as any).name : 'no file selected'}
                        readonly
                        >{ state.value
                            ? []
                            : <ion-icon slot="end" icon="cloud-upload"></ion-icon> }
                        </ion-input>
                        { state.value && <ion-icon slot="end" icon="close-circle-outline" onClick={(ev) => { ev.stopImmediatePropagation(); state.valueChanged(null); } }></ion-icon> }
                    
                  </div>
                );
            },
            multi(state: InputState) {
                const value = new Set(toArray(state.value));
                if (!Array.isArray(state.additionalSettings?.options)) {
                  return <ion-select
                    label={state.label}
                    label-placement="floating"
                    fill="outline"
                    value={state.value}
                    disabled>
                        <ion-select-option value={state.value}>{toString(value)}</ion-select-option>
                    </ion-select>
                }
              
                return <ion-select
                    interface="popover"
                    label={state.label}
                    label-placement="floating"
                    fill="outline"
                    multiple
                    disabled={state.disabled}
                    value={Array.from(value)}
                    onIonChange={(ev: any) => state.valueChanged(ev.target.value)}>
                  {state.additionalSettings.options.map((opt) => <ion-select-option value={toString(opt.value as any)}>{opt.name}</ion-select-option>)}
                  </ion-select>
            },
            select(state: InputState) {
                if (!Array.isArray(state.additionalSettings?.options)) {
                  return <ion-select
                    label={state.label}
                    label-placement="floating"
                    fill="outline"
                    value={state.value}
                    disabled>
                        <ion-select-option value={state.value}>{state.value}</ion-select-option>
                    </ion-select>
                }
              
                return <ion-select
                    interface="popover"
                    label={state.label}
                    label-placement="floating"
                    fill="outline"
                    disabled={state.disabled}
                    value={state.value}
                    onIonChange={(ev: any) => state.valueChanged(ev.target.value)}>
                  {state.additionalSettings.options.map((opt) => <ion-select-option value={toString(opt.value as any)}>{opt.name}</ion-select-option>)}
                  </ion-select>
            },
            */
        };
    }
}