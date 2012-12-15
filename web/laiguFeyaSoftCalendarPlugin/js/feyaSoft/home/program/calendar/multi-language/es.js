/**
 * FeyaSoft Online Calendar
 * Copyright(c) 2006-2009, FeyaSoft Inc. All right reserved.
 * info@feyasoft.com
 * http://www.feyasoft.com/myCalendar
 *
 * You need buy one of the Feyasoft's License if you want to use MyCalendar in
 * your commercial product.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
 * KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY,FITNESS FOR A PARTICULAR PURPOSE
 * AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
Ext.ns("Ext.ux.calendar");

Ext.ux.calendar.Language = {

    // please help to transfer words after :. Thanks
    'CalendarWin':{
        'title':'FeyaSoft MyCalendar 2.0.3',
        'loadMask.msg':'Por favor, espere...'
    },

    'MainPanel':{
        'loadMask.msg':'Por favor, espere...'
    },
    
    'SharingPopup':{
        'title':'Calendario compartido'
    },

    'CalendarContainer':{
        'todayBtn.text':'Hoy',
        'dayBtn.text':'Vista diaria',
        'weekBtn.text':'Vista semanal',
        'monthBtn.text':'Vista mensual',
        'weekMenu.showAll.text':'Mostrar todo',
        'weekMenu.onlyWeek.text':'Solo',
        'monthMenu.showAll.text':'Mostrar todo',
        'monthMenu.onlyWeek.text':'Lunes a Viernes',
        'moreMenu.setting.text':'Configuraci&oacute;n',
        'moreMenu.about.text':'Acerca de FeyaSoft MyCalendar',
        'moreBtn.text':'M&aacute;s',
        'searchCriteria.text':'B&uacute;squeda',        
        'moreMenu.showAlert.text':'Activar funci&oacute;n de alerta',
        'moreMenu.language.text':'Configuraci&oacute;n del lenguaje'
    },

    'WestPanel':{
        'myCalendarPanel.title':'My Calendar',
        'otherCalendarPanel.title':'Otro calendario',
        'myShowAllBtn.text':'Mostrar todo',
        'myAddBtn.text':'Nuevo'
    },

    'EventHandler':{
        'showOnlyItem.text':'Mostrar solo este',
        'viewItem.hide.text':'Esconder calendario',
        'viewItem.show.text':'Mostrar calendario',
        'editItem.text':'Editar calendario',
        'deleteItem.text':'Eliminar calendario',
        'clearItem.text':'Vaciar calendario',
        'wholeDay':'D&iacute;a completo',
        'untitled':'Sin t&iacute;tulo',
        'unlockItem.text':'Desproteger',
        'lockItem.text':'Proteger',
        'editEvent.title':'Editar Evento',
        'deleteEvent.title':'Eliminar Evento',
        'more':'M&aacute;s',
        'deleteRepeatPopup.title':'Confirmar',
        'deleteRepeatPopup.msg':'Seleccione "Si" para borrar todos los eventos que son parte de la repetici&oacute;n, o "No" para borrar solo esta ocurrencia',
        'updateRepeatPopup.title':'Confirmar',
        'updateRepeatPopup.msg':'Seleccione "Si" para actualizar todos los eventos que son parte de la repetici&oacute;n, o  "No" para solamente actualizar esta ocurrencia',
        'shareItem.text':'Compartir calendario'
    },

    'Editor':{
        'startDayField.label':'Inicio',
        'endDayField.label':'Hasta',
        'wholeField.label':'D&iacute;a completo',
        'subjectField.label':'Asunto',
        'contentField.label':'Contenido',
        'calendarField.label':'Calendario',
        'alertCB.label':'Alarma al activar',
        'lockCB.label':'Protegido',
        'deleteBtn.text':'Eliminar',
        'saveBtn.text':'Guardar',
        'cancelBtn.text':'Cancelar',
        'new.title':'Nuevo Evento',
        'edit.title':'Editar evento',
        'repeatTypeField.label':'Tipo de repetici&oacute;n',
        'repeatIntervalField.label':'Ocurre cada ',
        'intervalUnitLabel.day.text':' D&iacute;a(s) ',
        'intervalUnitLabel.week.text':' Semana(s) ',
        'intervalUnitLabel.month.text':' Mes(es) ',
        'intervalUnitLabel.year.text':' A�o(s) ',
        'detailSetting':'Modificar detalle...',
        'returnBtn.text':'Volver',
        'startAndEnd':'Inicio y fin',
        'repeatStartField.label':'Inicio',
        'repeatNoEndRG.label':'No antes de',
        'repeatEndTimeRG.label':'Finalizar despu&eacute;s de',
        'repeatEndDateRG.label':'Finalizar en',
        'repeatEndTimeUnit':'ocurrencia(s)',
        'weekCheckGroup.label':'D&iacute;a de repetici&oacute;n',
        'monthRadioGroup.label':'Repetir por',
        'repeatByDate':'Fecha',
        'repeatByDay':'D&iacute;a'        
    },
    
    'CalendarEditor':{
        'new.title':'Nuevo calendario',
        'edit.title':'Editar Calendario',
        'nameField.label':'Nombre',
        'descriptionField.label':'Descripci&oacute;n',
        'clearBtn.text':'Limpiar',
        'saveBtn.text':'Guardar',
        'cancelBtn.text':'Cancelar',
        'returnBtn.text':'Volver',
        'shareCalendar':'Compartir el calendario',
        'shareColumns.user':'Usuario',
        'shareColumns.permit':'Permiso',
        'shareColumns.add':'Agregar usuario para compartir',
        'shareColumns.remove':'Eliminar',
        'userField.emptyText':'Por favor ingrese el nombre de usuario o su email'
    },
    
    'ExpirePopup':{
        'hideCB.label':'No m&aacute;s avisos',
        'title':'Alerta de Evento',
        'tpl.calendar':'Calendario',
        'tpl.subject':'Asunto',
        'tpl.content':'Contenido',
        'tpl.leftTime':'Tiempo restante',
        'hour':'Hora(s)',
        'minute':'Minuto(s)',
        'untitled':'Sin t&iacute;tulo',
        'noContent':'Sin contenido'
    },

    'SettingPopup':{
        'title':'Configuraci&oacute;n del calendario',
        'hourFormatField.label':'Formato de la hora',
        'dayFormatField.label':'Formato del d&iacute;a para la vista diaria',
        'weekFormatField.label':'Formato del d&iacute;a para la vista semanal',
        'monthFormatField.label':'Formato del d&iacute;a para la vista mensual',
        'applyBtn.text':'Aplicar',
        'resetBtn.text':'Restaurar',
        'closeBtn.text':'Cerrar',
        'fromtoFormatField.label':'Formato De',
        'scrollStartRowField.label':'Fila de inicio para desplazamiento',
        'languageField.label':'Lenguaje',
        'generalForm.title':'General',
        'dwViewForm.title':'Vista diaria|Vista semanal',
        'monthViewForm.title':'Vista mensual',
        'createByDblClickField.label':'Crear evento por doble click',
        'singleDayField.label':'Evento d&iacute;as m&uacute;ltiples',
        'weekStartDayField.label': 'D&iacute;a de inicio de la semana',
        'activeStartTimeField.label':'Hora de inicio',
        'activeEndTimeField.label':'Hora de t&eacute;rmino',
        'hideInactiveTimeField.label':'Esconder horario fuera de rango',
        'readOnlyField.label':'S&oacute;lo lectura',
        'intervalField.label':'Intervalo de tiempo',
        'startEndInvalid':'Hora de inicio  debe ser anterior a la hora de t&eacute;rmino!',
        'formatInvalid':'Ejemplo: 09:00'
    },

    'ResultView':{
        'cm.date':'Fecha',
        'cm.calendar':'Calendario',
        'cm.time':'Hora',
        'cm.subject':'Asunto',
        'cm.content':'Contenido',
        'cm.expire':'Tiempo restante',
        'groupBtn.group.text':'Agrupar',
        'groupBtn.unGroup.text':'Desagrupar',
        'returnBtn.text':'Volver',
        'hour':'Hora(s)',
        'noSubject':'(Sin asunto)',
        'noContent':'(Sin contenido)',
        'loadMask.msg':'Por favor, espere...'
    },

    'DayView':{
        'loadMask.msg':'Por favor, espere...',
        'addItem.text':'Nuevo Evento',
        'events':'eventos'
    },

    'MonthView':{
        'loadMask.msg':'Por favor, espere...',
        'overview':'Vistazo previo',
        'showingEvents':'Mostrando eventos',
        'totalEvents':'Total de eventos',
        'dayPre':'',
        'addItem.text':'Nuevo Evento',
        'clearItem.text':'Limpiar evento',
        'cutItem.text':'Cortar',
        'copyItem.text':'Copiar',
        'pasteItem.text':'Pegar',
        'events':'eventos'
    },

    'Mask':{
        '12Hours':'12 Horas',
        '24Hours':'24 Horas',
        'ar': 'Arabe',
        'de': 'Aleman',
        'en_US':'Ingl&eacute;s americano',
        'es': 'Espa�ol',
        'fr': 'Franc&eacute;s',
        'it': 'Italiano',
        'ja': 'Japon&eacute;s',
        'nl': 'Holandes',
        'pl': 'Polaco',
        'pt': 'Portugu&eacute;s',
        'ru': 'Ruso',
        'zh_CN':'简体中文',
        'enable':'Habilitado',
        'disable':'Deshabilitado',
        'minute':'Minutos',
        'monday':'Lunes',
        'sunday':'Domingo',
        'permitData':[
            [0, 'Lectura, Escritura y Compartir'],
            [1, 'Lectura y Escritura'],
            [2, 'S&oacute;lo Lectura']
        ]
    },

    repeatType:[
        ['no', 'Sin repetici&oacute;n'],
        ['day', 'Diaria'],
        ['week', 'Semanal'],
        ['month', 'Mensual'],
        ['year', 'Anual']
    ],

    getWeekDayInMonth:function(date){
        var d = date.format('d');
        var w = Math.floor(d/7)+1;
        var wd = date.format('l');
        var str = 'el '+w;
        if(1 == w){
            str += 'o';
        }else if(2 == w){
            str += 'o';
        }else if(3 == w){
            str += 'er';
        }else{
            str += 'o';
        }
        return str+' '+wd;
    },

    getIntervalText:function(rtype, intervalSlot){
        var str = '';
        if('day' == rtype){
            if(1 == intervalSlot){
                str = 'Todos los d&iacute;as';
            }else{
                str = 'Cada '+intervalSlot+' d&iacute;as';
            }
        }else if('week' == rtype){
            if(1 == intervalSlot){
                str = 'Todas las semanas a partir de ';
            }else{
                str = 'Cada '+intervalSlot+' semanas a partir de ';
            }
        }else if('month' == rtype){
            if(1 == intervalSlot){
                str = 'Todos los meses a partir de ';
            }else{
                str = 'Cada '+intervalSlot+' meses a partir de ';
            }
        }else if('year' == rtype){
            if(1 == intervalSlot){
                str = 'Todos los a�os a partir de ';
            }else{
                str = 'Cada '+intervalSlot+' a�os a partir de ';
            }
        }
        return str;
    }
};

Ext.apply(Ext.ux.calendar.Mask, Ext.ux.calendar.Language);