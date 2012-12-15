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
Ext.onReady(function(){    
    Ext.BLANK_IMAGE_URL = Ext.ux.calendar.CONST.BLANK_IMAGE_URL;    
    Ext.QuickTips.init();
    var wait = new Ext.LoadMask(document.body, {msg:'<b>Welcome to FeyaSoft MyCalendar</b><br>Please wait, loading Setting...'});
    wait.show();
    
    //hard code userId here
    var userId = 1;    
    var ds = new Ext.ux.calendar.DataSource();    
    ds.initialLoad(userId, function(backObj){
        var cs = backObj.cs;
        ds.initialObj = backObj;
        if(!cs['language']){
            var params = Ext.urlDecode(window.location.search.substring(1));
            if(params.lang){
                cs.language = params.lang;
            }else{
                cs.language = 'en_US';
            }
        }
        /*
         * here add the related language file
         */
        if(Ext.ux.calendar.CONST.SHOW_LANGUAGE_MENU){
            Ext.ux.calendar.LanManager.addJavaScript(cs.language);
        }
        var count = 0;
        var fn = function(){
            if(!Ext.ux.calendar.Language && count++ < 40){
                /*
                 * need defer to wait the js file loaded
                 */
                fn.defer(50);
            }else{
                var win = new Ext.ux.calendar.CalendarWin({
                    iconCls:'icon_feyaCalendar_calendar',
                    title: 'FeyaSoft MyCalendar 2.0.5',
                    width:1200,
                    height:560,                    
                    maximizable:true,
                    minimizable:true,
                    shim:false,
                    animCollapse:false,
                    closable:true,
                    datasource:ds,
                    userId:userId,
                    calendarSetting:cs
                });                
                win.show();
                wait.hide();
            }
        };        
        fn();
    });
});