

Ext.DomHelper = function(){
    var tempTableEl = null,
        emptyTags = /^(?:br|frame|hr|img|input|link|meta|range|spacer|wbr|area|param|col)$/i,
        tableRe = /^table|tbody|tr|td$/i,
        confRe = /tag|children|cn|html$/i,
        tableElRe = /td|tr|tbody/i,
        cssRe = /([a-z0-9-]+)\s*:\s*([^;\s]+(?:\s*[^;\s]+)*);?/gi,
        endRe = /end/i,
        pub,
        
        afterbegin = 'afterbegin',
        afterend = 'afterend',
        beforebegin = 'beforebegin',
        beforeend = 'beforeend',
        ts = '<table>',
        te = '</table>',
        tbs = ts+'<tbody>',
        tbe = '</tbody>'+te,
        trs = tbs + '<tr>',
        tre = '</tr>'+tbe;

    
    function doInsert(el, o, returnElement, pos, sibling, append){
        var newNode = pub.insertHtml(pos, Ext.getDom(el), createHtml(o));
        return returnElement ? Ext.get(newNode, true) : newNode;
    }

    
    function createHtml(o){
        var b = '',
            attr,
            val,
            key,
            keyVal,
            cn;

        if(typeof o == "string"){
            b = o;
        } else if (Ext.isArray(o)) {
            for (var i=0; i < o.length; i++) {
                if(o[i]) {
                    b += createHtml(o[i]);
                }
            };
        } else {
            b += '<' + (o.tag = o.tag || 'div');
            for (attr in o) {
                val = o[attr];
                if(!confRe.test(attr)){
                    if (typeof val == "object") {
                        b += ' ' + attr + '="';
                        for (key in val) {
                            b += key + ':' + val[key] + ';';
                        };
                        b += '"';
                    }else{
                        b += ' ' + ({cls : 'class', htmlFor : 'for'}[attr] || attr) + '="' + val + '"';
                    }
                }
            };
            
            if (emptyTags.test(o.tag)) {
                b += '/>';
            } else {
                b += '>';
                if ((cn = o.children || o.cn)) {
                    b += createHtml(cn);
                } else if(o.html){
                    b += o.html;
                }
                b += '</' + o.tag + '>';
            }
        }
        return b;
    }

    function ieTable(depth, s, h, e){
        tempTableEl.innerHTML = [s, h, e].join('');
        var i = -1,
            el = tempTableEl,
            ns;
        while(++i < depth){
            el = el.firstChild;
        }

        if(ns = el.nextSibling){
            var df = document.createDocumentFragment();
            while(el){
                ns = el.nextSibling;
                df.appendChild(el);
                el = ns;
            }
            el = df;
        }
        return el;
    }

    
    function insertIntoTable(tag, where, el, html) {
        var node,
            before;

        tempTableEl = tempTableEl || document.createElement('div');

        if(tag == 'td' && (where == afterbegin || where == beforeend) ||
           !tableElRe.test(tag) && (where == beforebegin || where == afterend)) {
            return;
        }
        before = where == beforebegin ? el :
                 where == afterend ? el.nextSibling :
                 where == afterbegin ? el.firstChild : null;

        if (where == beforebegin || where == afterend) {
            el = el.parentNode;
        }

        if (tag == 'td' || (tag == 'tr' && (where == beforeend || where == afterbegin))) {
            node = ieTable(4, trs, html, tre);
        } else if ((tag == 'tbody' && (where == beforeend || where == afterbegin)) ||
                   (tag == 'tr' && (where == beforebegin || where == afterend))) {
            node = ieTable(3, tbs, html, tbe);
        } else {
            node = ieTable(2, ts, html, te);
        }
        el.insertBefore(node, before);
        return node;
    }


    pub = {
        
        markup : function(o){
            return createHtml(o);
        },

        
        applyStyles : function(el, styles){
            if(styles){
                var i = 0,
                    len,
                    style,
                    matches;

                el = Ext.fly(el);
                if(typeof styles == "function"){
                    styles = styles.call();
                }
                if(typeof styles == "string"){
                    while((matches = cssRe.exec(styles))){
                        el.setStyle(matches[1], matches[2]);
                    }
                }else if (typeof styles == "object"){
                    el.setStyle(styles);
                }
            }
        },

        
        insertHtml : function(where, el, html){
            var hash = {},
                hashVal,
                setStart,
                range,
                frag,
                rangeEl,
                rs;

            where = where.toLowerCase();
            
            hash[beforebegin] = ['BeforeBegin', 'previousSibling'];
            hash[afterend] = ['AfterEnd', 'nextSibling'];

            if (el.insertAdjacentHTML) {
                if(tableRe.test(el.tagName) && (rs = insertIntoTable(el.tagName.toLowerCase(), where, el, html))){
                    return rs;
                }
                
                hash[afterbegin] = ['AfterBegin', 'firstChild'];
                hash[beforeend] = ['BeforeEnd', 'lastChild'];
                if ((hashVal = hash[where])) {
                    el.insertAdjacentHTML(hashVal[0], html);
                    return el[hashVal[1]];
                }
            } else {
                range = el.ownerDocument.createRange();
                setStart = 'setStart' + (endRe.test(where) ? 'After' : 'Before');
                if (hash[where]) {
                    range[setStart](el);
                    frag = range.createContextualFragment(html);
                    el.parentNode.insertBefore(frag, where == beforebegin ? el : el.nextSibling);
                    return el[(where == beforebegin ? 'previous' : 'next') + 'Sibling'];
                } else {
                    rangeEl = (where == afterbegin ? 'first' : 'last') + 'Child';
                    if (el.firstChild) {
                        range[setStart](el[rangeEl]);
                        frag = range.createContextualFragment(html);
                        if(where == afterbegin){
                            el.insertBefore(frag, el.firstChild);
                        }else{
                            el.appendChild(frag);
                        }
                    } else {
                        el.innerHTML = html;
                    }
                    return el[rangeEl];
                }
            }
            throw 'Illegal insertion point -> "' + where + '"';
        },

        
        insertBefore : function(el, o, returnElement){
            return doInsert(el, o, returnElement, beforebegin);
        },

        
        insertAfter : function(el, o, returnElement){
            return doInsert(el, o, returnElement, afterend, 'nextSibling');
        },

        
        insertFirst : function(el, o, returnElement){
            return doInsert(el, o, returnElement, afterbegin, 'firstChild');
        },

        
        append : function(el, o, returnElement){
            return doInsert(el, o, returnElement, beforeend, '', true);
        },

        
        overwrite : function(el, o, returnElement){
            el = Ext.getDom(el);
            el.innerHTML = createHtml(o);
            return returnElement ? Ext.get(el.firstChild) : el.firstChild;
        },

        createHtml : createHtml
    };
    return pub;
}();

Ext.apply(Ext.DomHelper,
function(){
    var pub,
        afterbegin = 'afterbegin',
        afterend = 'afterend',
        beforebegin = 'beforebegin',
        beforeend = 'beforeend',
        confRe = /tag|children|cn|html$/i;

    
    function doInsert(el, o, returnElement, pos, sibling, append){
        el = Ext.getDom(el);
        var newNode;
        if (pub.useDom) {
            newNode = createDom(o, null);
            if (append) {
                el.appendChild(newNode);
            } else {
                (sibling == 'firstChild' ? el : el.parentNode).insertBefore(newNode, el[sibling] || el);
            }
        } else {
            newNode = Ext.DomHelper.insertHtml(pos, el, Ext.DomHelper.createHtml(o));
        }
        return returnElement ? Ext.get(newNode, true) : newNode;
    }

    
    
    function createDom(o, parentNode){
        var el,
            doc = document,
            useSet,
            attr,
            val,
            cn;

        if (Ext.isArray(o)) {                       
            el = doc.createDocumentFragment(); 
            for (var i = 0, l = o.length; i < l; i++) {
                createDom(o[i], el);
            }
        } else if (typeof o == 'string') {         
            el = doc.createTextNode(o);
        } else {
            el = doc.createElement( o.tag || 'div' );
            useSet = !!el.setAttribute; 
            for (var attr in o) {
                if(!confRe.test(attr)){
                    val = o[attr];
                    if(attr == 'cls'){
                        el.className = val;
                    }else{
                        if(useSet){
                            el.setAttribute(attr, val);
                        }else{
                            el[attr] = val;
                        }
                    }
                }
            }
            Ext.DomHelper.applyStyles(el, o.style);

            if ((cn = o.children || o.cn)) {
                createDom(cn, el);
            } else if (o.html) {
                el.innerHTML = o.html;
            }
        }
        if(parentNode){
           parentNode.appendChild(el);
        }
        return el;
    }

    pub = {
        
        createTemplate : function(o){
            var html = Ext.DomHelper.createHtml(o);
            return new Ext.Template(html);
        },

        
        useDom : false,

        
        insertBefore : function(el, o, returnElement){
            return doInsert(el, o, returnElement, beforebegin);
        },

        
        insertAfter : function(el, o, returnElement){
            return doInsert(el, o, returnElement, afterend, 'nextSibling');
        },

        
        insertFirst : function(el, o, returnElement){
            return doInsert(el, o, returnElement, afterbegin, 'firstChild');
        },

        
        append: function(el, o, returnElement){
            return doInsert(el, o, returnElement, beforeend, '', true);
        },

        
        createDom: createDom
    };
    return pub;
}());

Ext.Template = function(html){
    var me = this,
        a = arguments,
        buf = [],
        v;

    if (Ext.isArray(html)) {
        html = html.join("");
    } else if (a.length > 1) {
        for(var i = 0, len = a.length; i < len; i++){
            v = a[i];
            if(typeof v == 'object'){
                Ext.apply(me, v);
            } else {
                buf.push(v);
            }
        };
        html = buf.join('');
    }

    
    me.html = html;
    
    if (me.compiled) {
        me.compile();
    }
};
Ext.Template.prototype = {
    
    re : /\{([\w-]+)\}/g,
    

    
    applyTemplate : function(values){
        var me = this;

        return me.compiled ?
                me.compiled(values) :
                me.html.replace(me.re, function(m, name){
                    return values[name] !== undefined ? values[name] : "";
                });
    },

    
    set : function(html, compile){
        var me = this;
        me.html = html;
        me.compiled = null;
        return compile ? me.compile() : me;
    },

    
    compile : function(){
        var me = this,
            sep = Ext.isGecko ? "+" : ",";

        function fn(m, name){
            name = "values['" + name + "']";
            return "'"+ sep + '(' + name + " == undefined ? '' : " + name + ')' + sep + "'";
        }

        eval("this.compiled = function(values){ return " + (Ext.isGecko ? "'" : "['") +
             me.html.replace(/\\/g, '\\\\').replace(/(\r\n|\n)/g, '\\n').replace(/'/g, "\\'").replace(this.re, fn) +
             (Ext.isGecko ?  "';};" : "'].join('');};"));
        return me;
    },

    
    insertFirst: function(el, values, returnElement){
        return this.doInsert('afterBegin', el, values, returnElement);
    },

    
    insertBefore: function(el, values, returnElement){
        return this.doInsert('beforeBegin', el, values, returnElement);
    },

    
    insertAfter : function(el, values, returnElement){
        return this.doInsert('afterEnd', el, values, returnElement);
    },

    
    append : function(el, values, returnElement){
        return this.doInsert('beforeEnd', el, values, returnElement);
    },

    doInsert : function(where, el, values, returnEl){
        el = Ext.getDom(el);
        var newNode = Ext.DomHelper.insertHtml(where, el, this.applyTemplate(values));
        return returnEl ? Ext.get(newNode, true) : newNode;
    },

    
    overwrite : function(el, values, returnElement){
        el = Ext.getDom(el);
        el.innerHTML = this.applyTemplate(values);
        return returnElement ? Ext.get(el.firstChild, true) : el.firstChild;
    }
};

Ext.Template.prototype.apply = Ext.Template.prototype.applyTemplate;


Ext.Template.from = function(el, config){
    el = Ext.getDom(el);
    return new Ext.Template(el.value || el.innerHTML, config || '');
};

Ext.apply(Ext.Template.prototype, {
    
    disableFormats : false,
    

    
    re : /\{([\w-]+)(?:\:([\w\.]*)(?:\((.*?)?\))?)?\}/g,
    argsRe : /^\s*['"](.*)["']\s*$/,
    compileARe : /\\/g,
    compileBRe : /(\r\n|\n)/g,
    compileCRe : /'/g,

    /**
     * Returns an HTML fragment of this template with the specified values applied.
     * @param {Object/Array} values The template values. Can be an array if your params are numeric (i.e. {0}) or an object (i.e. {foo: 'bar'})
     * @return {String} The HTML fragment
     * @hide repeat doc
     */
    applyTemplate : function(values){
        var me = this,
            useF = me.disableFormats !== true,
            fm = Ext.util.Format,
            tpl = me;

        if(me.compiled){
            return me.compiled(values);
        }
        function fn(m, name, format, args){
            if (format && useF) {
                if (format.substr(0, 5) == "this.") {
                    return tpl.call(format.substr(5), values[name], values);
                } else {
                    if (args) {
                        // quoted values are required for strings in compiled templates,
                        // but for non compiled we need to strip them
                        // quoted reversed for jsmin
                        var re = me.argsRe;
                        args = args.split(',');
                        for(var i = 0, len = args.length; i < len; i++){
                            args[i] = args[i].replace(re, "$1");
                        }
                        args = [values[name]].concat(args);
                    } else {
                        args = [values[name]];
                    }
                    return fm[format].apply(fm, args);
                }
            } else {
                return values[name] !== undefined ? values[name] : "";
            }
        }
        return me.html.replace(me.re, fn);
    },

    /**
     * Compiles the template into an internal function, eliminating the RegEx overhead.
     * @return {Ext.Template} this
     * @hide repeat doc
     */
    compile : function(){
        var me = this,
            fm = Ext.util.Format,
            useF = me.disableFormats !== true,
            sep = Ext.isGecko ? "+" : ",",
            body;

        function fn(m, name, format, args){
            if(format && useF){
                args = args ? ',' + args : "";
                if(format.substr(0, 5) != "this."){
                    format = "fm." + format + '(';
                }else{
                    format = 'this.call("'+ format.substr(5) + '", ';
                    args = ", values";
                }
            }else{
                args= ''; format = "(values['" + name + "'] == undefined ? '' : ";
            }
            return "'"+ sep + format + "values['" + name + "']" + args + ")"+sep+"'";
        }

        // branched to use + in gecko and [].join() in others
        if(Ext.isGecko){
            body = "this.compiled = function(values){ return '" +
                   me.html.replace(me.compileARe, '\\\\').replace(me.compileBRe, '\\n').replace(me.compileCRe, "\\'").replace(me.re, fn) +
                    "';};";
        }else{
            body = ["this.compiled = function(values){ return ['"];
            body.push(me.html.replace(me.compileARe, '\\\\').replace(me.compileBRe, '\\n').replace(me.compileCRe, "\\'").replace(me.re, fn));
            body.push("'].join('');};");
            body = body.join('');
        }
        eval(body);
        return me;
    },

    // private function used to call members
    call : function(fnName, value, allValues){
        return this[fnName](value, allValues);
    }
});
Ext.Template.prototype.apply = Ext.Template.prototype.applyTemplate;
/*
 * This is code is also distributed under MIT license for use
 * with jQuery and prototype JavaScript libraries.
 */
/**
 * @class Ext.DomQuery
Provides high performance selector/xpath processing by compiling queries into reusable functions. New pseudo classes and matchers can be plugged. It works on HTML and XML documents (if a content node is passed in).
<p>
DomQuery supports most of the <a href="http://www.w3.org/TR/2005/WD-css3-selectors-20051215/#selectors">CSS3 selectors spec</a>, along with some custom selectors and basic XPath.</p>

<p>
All selectors, attribute filters and pseudos below can be combined infinitely in any order. For example "div.foo:nth-child(odd)[@foo=bar].bar:first" would be a perfectly valid selector. Node filters are processed in the order in which they appear, which allows you to optimize your queries for your document structure.
</p>
<h4>Element Selectors:</h4>
<ul class="list">
    <li> <b>*</b> any element</li>
    <li> <b>E</b> an element with the tag E</li>
    <li> <b>E F</b> All descendent elements of E that have the tag F</li>
    <li> <b>E > F</b> or <b>E/F</b> all direct children elements of E that have the tag F</li>
    <li> <b>E + F</b> all elements with the tag F that are immediately preceded by an element with the tag E</li>
    <li> <b>E ~ F</b> all elements with the tag F that are preceded by a sibling element with the tag E</li>
</ul>
<h4>Attribute Selectors:</h4>
<p>The use of &#64; and quotes are optional. For example, div[&#64;foo='bar'] is also a valid attribute selector.</p>
<ul class="list">
    <li> <b>E[foo]</b> has an attribute "foo"</li>
    <li> <b>E[foo=bar]</b> has an attribute "foo" that equals "bar"</li>
    <li> <b>E[foo^=bar]</b> has an attribute "foo" that starts with "bar"</li>
    <li> <b>E[foo$=bar]</b> has an attribute "foo" that ends with "bar"</li>
    <li> <b>E[foo*=bar]</b> has an attribute "foo" that contains the substring "bar"</li>
    <li> <b>E[foo%=2]</b> has an attribute "foo" that is evenly divisible by 2</li>
    <li> <b>E[foo!=bar]</b> has an attribute "foo" that does not equal "bar"</li>
</ul>
<h4>Pseudo Classes:</h4>
<ul class="list">
    <li> <b>E:first-child</b> E is the first child of its parent</li>
    <li> <b>E:last-child</b> E is the last child of its parent</li>
    <li> <b>E:nth-child(<i>n</i>)</b> E is the <i>n</i>th child of its parent (1 based as per the spec)</li>
    <li> <b>E:nth-child(odd)</b> E is an odd child of its parent</li>
    <li> <b>E:nth-child(even)</b> E is an even child of its parent</li>
    <li> <b>E:only-child</b> E is the only child of its parent</li>
    <li> <b>E:checked</b> E is an element that is has a checked attribute that is true (e.g. a radio or checkbox) </li>
    <li> <b>E:first</b> the first E in the resultset</li>
    <li> <b>E:last</b> the last E in the resultset</li>
    <li> <b>E:nth(<i>n</i>)</b> the <i>n</i>th E in the resultset (1 based)</li>
    <li> <b>E:odd</b> shortcut for :nth-child(odd)</li>
    <li> <b>E:even</b> shortcut for :nth-child(even)</li>
    <li> <b>E:contains(foo)</b> E's innerHTML contains the substring "foo"</li>
    <li> <b>E:nodeValue(foo)</b> E contains a textNode with a nodeValue that equals "foo"</li>
    <li> <b>E:not(S)</b> an E element that does not match simple selector S</li>
    <li> <b>E:has(S)</b> an E element that has a descendent that matches simple selector S</li>
    <li> <b>E:next(S)</b> an E element whose next sibling matches simple selector S</li>
    <li> <b>E:prev(S)</b> an E element whose previous sibling matches simple selector S</li>
    <li> <b>E:any(S1|S2|S2)</b> an E element which matches any of the simple selectors S1, S2 or S3
</ul>
<h4>CSS Value Selectors:</h4>
<ul class="list">
    <li> <b>E{display=none}</b> css value "display" that equals "none"</li>
    <li> <b>E{display^=none}</b> css value "display" that starts with "none"</li>
    <li> <b>E{display$=none}</b> css value "display" that ends with "none"</li>
    <li> <b>E{display*=none}</b> css value "display" that contains the substring "none"</li>
    <li> <b>E{display%=2}</b> css value "display" that is evenly divisible by 2</li>
    <li> <b>E{display!=none}</b> css value "display" that does not equal "none"</li>
</ul>
 * @singleton
 */
Ext.DomQuery = function(){
    var cache = {}, 
    	simpleCache = {}, 
    	valueCache = {},
    	nonSpace = /\S/,
    	trimRe = /^\s+|\s+$/g,
    	tplRe = /\{(\d+)\}/g,
    	modeRe = /^(\s?[\/>+~]\s?|\s|$)/,
    	tagTokenRe = /^(#)?([\w-\*]+)/,
    	nthRe = /(\d*)n\+?(\d*)/, 
    	nthRe2 = /\D/,
    	
	
	
	isIE = window.ActiveXObject ? true : false,
	key = 30803;
    
    
    
    eval("var batch = 30803;");    	

    
    
    function child(parent, index){
        var i = 0,
            n = parent.firstChild;
        while(n){
            if(n.nodeType == 1){
               if(++i == index){
                   return n;
               }
            }
            n = n.nextSibling;
        }
        return null;
    }

    
    function next(n){	
        while((n = n.nextSibling) && n.nodeType != 1);
        return n;
    }

    
    function prev(n){
        while((n = n.previousSibling) && n.nodeType != 1);
        return n;
    }

    
    
    function children(parent){
        var n = parent.firstChild,
	    nodeIndex = -1,
	    nextNode;
	while(n){
	    nextNode = n.nextSibling;
	    
	    if(n.nodeType == 3 && !nonSpace.test(n.nodeValue)){
		parent.removeChild(n);
	    }else{
		
		n.nodeIndex = ++nodeIndex;
	    }
	    n = nextNode;
	}
	return this;
    }


    
    
    function byClassName(nodeSet, cls){
        if(!cls){
            return nodeSet;
        }
        var result = [], ri = -1;
        for(var i = 0, ci; ci = nodeSet[i]; i++){
            if((' '+ci.className+' ').indexOf(cls) != -1){
                result[++ri] = ci;
            }
        }
        return result;
    };

    function attrValue(n, attr){
	
        if(!n.tagName && typeof n.length != "undefined"){
            n = n[0];
        }
        if(!n){
            return null;
        }

        if(attr == "for"){
            return n.htmlFor;
        }
        if(attr == "class" || attr == "className"){
            return n.className;
        }
        return n.getAttribute(attr) || n[attr];

    };


    
    
    
    function getNodes(ns, mode, tagName){
        var result = [], ri = -1, cs;
        if(!ns){
            return result;
        }
        tagName = tagName || "*";
	
        if(typeof ns.getElementsByTagName != "undefined"){
            ns = [ns];
        }
	
	
	
        if(!mode){
            for(var i = 0, ni; ni = ns[i]; i++){
                cs = ni.getElementsByTagName(tagName);
                for(var j = 0, ci; ci = cs[j]; j++){
                    result[++ri] = ci;
                }
            }
	
	
        } else if(mode == "/" || mode == ">"){
            var utag = tagName.toUpperCase();
            for(var i = 0, ni, cn; ni = ns[i]; i++){
                cn = ni.childNodes;
                for(var j = 0, cj; cj = cn[j]; j++){
                    if(cj.nodeName == utag || cj.nodeName == tagName  || tagName == '*'){
                        result[++ri] = cj;
                    }
                }
            }
	
	
        }else if(mode == "+"){
            var utag = tagName.toUpperCase();
            for(var i = 0, n; n = ns[i]; i++){
                while((n = n.nextSibling) && n.nodeType != 1);
                if(n && (n.nodeName == utag || n.nodeName == tagName || tagName == '*')){
                    result[++ri] = n;
                }
            }
	
	
        }else if(mode == "~"){
            var utag = tagName.toUpperCase();
            for(var i = 0, n; n = ns[i]; i++){
                while((n = n.nextSibling)){
                    if (n.nodeName == utag || n.nodeName == tagName || tagName == '*'){
                        result[++ri] = n;
                    }
                }
            }
        }
        return result;
    }

    function concat(a, b){
        if(b.slice){
            return a.concat(b);
        }
        for(var i = 0, l = b.length; i < l; i++){
            a[a.length] = b[i];
        }
        return a;
    }

    function byTag(cs, tagName){
        if(cs.tagName || cs == document){
            cs = [cs];
        }
        if(!tagName){
            return cs;
        }
        var result = [], ri = -1;
        tagName = tagName.toLowerCase();
        for(var i = 0, ci; ci = cs[i]; i++){
            if(ci.nodeType == 1 && ci.tagName.toLowerCase() == tagName){
                result[++ri] = ci;
            }
        }
        return result;
    }

    function byId(cs, id){
        if(cs.tagName || cs == document){
            cs = [cs];
        }
        if(!id){
            return cs;
        }
        var result = [], ri = -1;
        for(var i = 0, ci; ci = cs[i]; i++){
            if(ci && ci.id == id){
                result[++ri] = ci;
                return result;
            }
        }
        return result;
    }

    
    
    function byAttribute(cs, attr, value, op, custom){
        var result = [], 
            ri = -1, 
            useGetStyle = custom == "{",	    
            fn = Ext.DomQuery.operators[op],	    
            a,	    
            innerHTML;
        for(var i = 0, ci; ci = cs[i]; i++){
	    
            if(ci.nodeType != 1){
                continue;
            }
	    
            innerHTML = ci.innerHTML;
            
            if(innerHTML !== null && innerHTML !== undefined){
                if(useGetStyle){
                    a = Ext.DomQuery.getStyle(ci, attr);
                } else if (attr == "class" || attr == "className"){
                    a = ci.className;
                } else if (attr == "for"){
                    a = ci.htmlFor;
                } else if (attr == "href"){
		    
		    
                    a = ci.getAttribute("href", 2);
                } else{
                    a = ci.getAttribute(attr);
                }
            }else{
                a = ci.getAttribute(attr);
            }
            if((fn && fn(a, value)) || (!fn && a)){
                result[++ri] = ci;
            }
        }
        return result;
    }

    function byPseudo(cs, name, value){
        return Ext.DomQuery.pseudos[name](cs, value);
    }

    function nodupIEXml(cs){
        var d = ++key, 
            r;
        cs[0].setAttribute("_nodup", d);
        r = [cs[0]];
        for(var i = 1, len = cs.length; i < len; i++){
            var c = cs[i];
            if(!c.getAttribute("_nodup") != d){
                c.setAttribute("_nodup", d);
                r[r.length] = c;
            }
        }
        for(var i = 0, len = cs.length; i < len; i++){
            cs[i].removeAttribute("_nodup");
        }
        return r;
    }

    function nodup(cs){
        if(!cs){
            return [];
        }
        var len = cs.length, c, i, r = cs, cj, ri = -1;
        if(!len || typeof cs.nodeType != "undefined" || len == 1){
            return cs;
        }
        if(isIE && typeof cs[0].selectSingleNode != "undefined"){
            return nodupIEXml(cs);
        }
        var d = ++key;
        cs[0]._nodup = d;
        for(i = 1; c = cs[i]; i++){
            if(c._nodup != d){
                c._nodup = d;
            }else{
                r = [];
                for(var j = 0; j < i; j++){
                    r[++ri] = cs[j];
                }
                for(j = i+1; cj = cs[j]; j++){
                    if(cj._nodup != d){
                        cj._nodup = d;
                        r[++ri] = cj;
                    }
                }
                return r;
            }
        }
        return r;
    }

    function quickDiffIEXml(c1, c2){
        var d = ++key,
            r = [];
        for(var i = 0, len = c1.length; i < len; i++){
            c1[i].setAttribute("_qdiff", d);
        }        
        for(var i = 0, len = c2.length; i < len; i++){
            if(c2[i].getAttribute("_qdiff") != d){
                r[r.length] = c2[i];
            }
        }
        for(var i = 0, len = c1.length; i < len; i++){
           c1[i].removeAttribute("_qdiff");
        }
        return r;
    }

    function quickDiff(c1, c2){
        var len1 = c1.length,
        	d = ++key,
        	r = [];
        if(!len1){
            return c2;
        }
        if(isIE && typeof c1[0].selectSingleNode != "undefined"){
            return quickDiffIEXml(c1, c2);
        }        
        for(var i = 0; i < len1; i++){
            c1[i]._qdiff = d;
        }        
        for(var i = 0, len = c2.length; i < len; i++){
            if(c2[i]._qdiff != d){
                r[r.length] = c2[i];
            }
        }
        return r;
    }

    function quickId(ns, mode, root, id){
        if(ns == root){
           var d = root.ownerDocument || root;
           return d.getElementById(id);
        }
        ns = getNodes(ns, mode, "*");
        return byId(ns, id);
    }

    return {
        getStyle : function(el, name){
            return Ext.fly(el).getStyle(name);
        },
        
        compile : function(path, type){
            type = type || "select";

	    
            var fn = ["var f = function(root){\n var mode; ++batch; var n = root || document;\n"],
		mode,		
		lastPath,
            	matchers = Ext.DomQuery.matchers,
            	matchersLn = matchers.length,
            	modeMatch,
            	
            	lmode = path.match(modeRe);
            
            if(lmode && lmode[1]){
                fn[fn.length] = 'mode="'+lmode[1].replace(trimRe, "")+'";';
                path = path.replace(lmode[1], "");
            }
	    
            
            while(path.substr(0, 1)=="/"){
                path = path.substr(1);
            }

            while(path && lastPath != path){
                lastPath = path;
                var tokenMatch = path.match(tagTokenRe);
                if(type == "select"){
                    if(tokenMatch){
			
                        if(tokenMatch[1] == "#"){
                            fn[fn.length] = 'n = quickId(n, mode, root, "'+tokenMatch[2]+'");';			
                        }else{
                            fn[fn.length] = 'n = getNodes(n, mode, "'+tokenMatch[2]+'");';
                        }
                        path = path.replace(tokenMatch[0], "");
                    }else if(path.substr(0, 1) != '@'){
                        fn[fn.length] = 'n = getNodes(n, mode, "*");';
                    }
		
                }else{
                    if(tokenMatch){
                        if(tokenMatch[1] == "#"){
                            fn[fn.length] = 'n = byId(n, "'+tokenMatch[2]+'");';
                        }else{
                            fn[fn.length] = 'n = byTag(n, "'+tokenMatch[2]+'");';
                        }
                        path = path.replace(tokenMatch[0], "");
                    }
                }
                while(!(modeMatch = path.match(modeRe))){
                    var matched = false;
                    for(var j = 0; j < matchersLn; j++){
                        var t = matchers[j];
                        var m = path.match(t.re);
                        if(m){
                            fn[fn.length] = t.select.replace(tplRe, function(x, i){
				return m[i];
			    });
                            path = path.replace(m[0], "");
                            matched = true;
                            break;
                        }
                    }
                    
                    if(!matched){
                        throw 'Error parsing selector, parsing failed at "' + path + '"';
                    }
                }
                if(modeMatch[1]){
                    fn[fn.length] = 'mode="'+modeMatch[1].replace(trimRe, "")+'";';
                    path = path.replace(modeMatch[1], "");
                }
            }
	    
            fn[fn.length] = "return nodup(n);\n}";
	    
	    
            eval(fn.join(""));
            return f;
        },

        
	jsSelect: function(path, root, type){
	    
	    root = root || document;
	    
            if(typeof root == "string"){
                root = document.getElementById(root);
            }
            var paths = path.split(","),
            	results = [];
		
	    
            for(var i = 0, len = paths.length; i < len; i++){		
                var subPath = paths[i].replace(trimRe, "");
		
                if(!cache[subPath]){
                    cache[subPath] = Ext.DomQuery.compile(subPath);
                    if(!cache[subPath]){
                        throw subPath + " is not a valid selector";
                    }
                }
                var result = cache[subPath](root);
                if(result && result != document){
                    results = results.concat(result);
                }
            }
	    
	    
	    
            if(paths.length > 1){
                return nodup(results);
            }
            return results;
        },
	isXml: function(el) {
	    var docEl = (el ? el.ownerDocument || el : 0).documentElement;
	    return docEl ? docEl.nodeName !== "HTML" : false;
	},
        select : document.querySelectorAll ? function(path, root, type) {
	    root = root || document;
	    if (!Ext.DomQuery.isXml(root)) {
		try {
		    var cs = root.querySelectorAll(path);
		    return Ext.toArray(cs);
		}
		catch (ex) {}		
	    }	    
	    return Ext.DomQuery.jsSelect.call(this, path, root, type);
	} : function(path, root, type) {
	    return Ext.DomQuery.jsSelect.call(this, path, root, type);
	},

        
        selectNode : function(path, root){
            return Ext.DomQuery.select(path, root)[0];
        },

        
        selectValue : function(path, root, defaultValue){
            path = path.replace(trimRe, "");
            if(!valueCache[path]){
                valueCache[path] = Ext.DomQuery.compile(path, "select");
            }
            var n = valueCache[path](root), v;
            n = n[0] ? n[0] : n;
            	    
	    
	    
	    
	    
            if (typeof n.normalize == 'function') n.normalize();
            
            v = (n && n.firstChild ? n.firstChild.nodeValue : null);
            return ((v === null||v === undefined||v==='') ? defaultValue : v);
        },

        
        selectNumber : function(path, root, defaultValue){
            var v = Ext.DomQuery.selectValue(path, root, defaultValue || 0);
            return parseFloat(v);
        },

        
        is : function(el, ss){
            if(typeof el == "string"){
                el = document.getElementById(el);
            }
            var isArray = Ext.isArray(el),
            	result = Ext.DomQuery.filter(isArray ? el : [el], ss);
            return isArray ? (result.length == el.length) : (result.length > 0);
        },

        
        filter : function(els, ss, nonMatches){
            ss = ss.replace(trimRe, "");
            if(!simpleCache[ss]){
                simpleCache[ss] = Ext.DomQuery.compile(ss, "simple");
            }
            var result = simpleCache[ss](els);
            return nonMatches ? quickDiff(result, els) : result;
        },

        
        matchers : [{
                re: /^\.([\w-]+)/,
                select: 'n = byClassName(n, " {1} ");'
            }, {
                re: /^\:([\w-]+)(?:\(((?:[^\s>\/]*|.*?))\))?/,
                select: 'n = byPseudo(n, "{1}", "{2}");'
            },{
                re: /^(?:([\[\{])(?:@)?([\w-]+)\s?(?:(=|.=)\s?['"]?(.*?)["']?)?[\]\}])/,
                select: 'n = byAttribute(n, "{2}", "{4}", "{3}", "{1}");'
            }, {
                re: /^#([\w-]+)/,
                select: 'n = byId(n, "{1}");'
            },{
                re: /^@([\w-]+)/,
                select: 'return {firstChild:{nodeValue:attrValue(n, "{1}")}};'
            }
        ],

        
        operators : {
            "=" : function(a, v){
                return a == v;
            },
            "!=" : function(a, v){
                return a != v;
            },
            "^=" : function(a, v){
                return a && a.substr(0, v.length) == v;
            },
            "$=" : function(a, v){
                return a && a.substr(a.length-v.length) == v;
            },
            "*=" : function(a, v){
                return a && a.indexOf(v) !== -1;
            },
            "%=" : function(a, v){
                return (a % v) == 0;
            },
            "|=" : function(a, v){
                return a && (a == v || a.substr(0, v.length+1) == v+'-');
            },
            "~=" : function(a, v){
                return a && (' '+a+' ').indexOf(' '+v+' ') != -1;
            }
        },

        
        pseudos : {
            "first-child" : function(c){
                var r = [], ri = -1, n;
                for(var i = 0, ci; ci = n = c[i]; i++){
                    while((n = n.previousSibling) && n.nodeType != 1);
                    if(!n){
                        r[++ri] = ci;
                    }
                }
                return r;
            },

            "last-child" : function(c){
                var r = [], ri = -1, n;
                for(var i = 0, ci; ci = n = c[i]; i++){
                    while((n = n.nextSibling) && n.nodeType != 1);
                    if(!n){
                        r[++ri] = ci;
                    }
                }
                return r;
            },

            "nth-child" : function(c, a) {
                var r = [], ri = -1,
                	m = nthRe.exec(a == "even" && "2n" || a == "odd" && "2n+1" || !nthRe2.test(a) && "n+" + a || a),
                	f = (m[1] || 1) - 0, l = m[2] - 0;
                for(var i = 0, n; n = c[i]; i++){
                    var pn = n.parentNode;
                    if (batch != pn._batch) {
                        var j = 0;
                        for(var cn = pn.firstChild; cn; cn = cn.nextSibling){
                            if(cn.nodeType == 1){
                               cn.nodeIndex = ++j;
                            }
                        }
                        pn._batch = batch;
                    }
                    if (f == 1) {
                        if (l == 0 || n.nodeIndex == l){
                            r[++ri] = n;
                        }
                    } else if ((n.nodeIndex + l) % f == 0){
                        r[++ri] = n;
                    }
                }

                return r;
            },

            "only-child" : function(c){
                var r = [], ri = -1;;
                for(var i = 0, ci; ci = c[i]; i++){
                    if(!prev(ci) && !next(ci)){
                        r[++ri] = ci;
                    }
                }
                return r;
            },

            "empty" : function(c){
                var r = [], ri = -1;
                for(var i = 0, ci; ci = c[i]; i++){
                    var cns = ci.childNodes, j = 0, cn, empty = true;
                    while(cn = cns[j]){
                        ++j;
                        if(cn.nodeType == 1 || cn.nodeType == 3){
                            empty = false;
                            break;
                        }
                    }
                    if(empty){
                        r[++ri] = ci;
                    }
                }
                return r;
            },

            "contains" : function(c, v){
                var r = [], ri = -1;
                for(var i = 0, ci; ci = c[i]; i++){
                    if((ci.textContent||ci.innerText||'').indexOf(v) != -1){
                        r[++ri] = ci;
                    }
                }
                return r;
            },

            "nodeValue" : function(c, v){
                var r = [], ri = -1;
                for(var i = 0, ci; ci = c[i]; i++){
                    if(ci.firstChild && ci.firstChild.nodeValue == v){
                        r[++ri] = ci;
                    }
                }
                return r;
            },

            "checked" : function(c){
                var r = [], ri = -1;
                for(var i = 0, ci; ci = c[i]; i++){
                    if(ci.checked == true){
                        r[++ri] = ci;
                    }
                }
                return r;
            },

            "not" : function(c, ss){
                return Ext.DomQuery.filter(c, ss, true);
            },

            "any" : function(c, selectors){
                var ss = selectors.split('|'),
                	r = [], ri = -1, s;
                for(var i = 0, ci; ci = c[i]; i++){
                    for(var j = 0; s = ss[j]; j++){
                        if(Ext.DomQuery.is(ci, s)){
                            r[++ri] = ci;
                            break;
                        }
                    }
                }
                return r;
            },

            "odd" : function(c){
                return this["nth-child"](c, "odd");
            },

            "even" : function(c){
                return this["nth-child"](c, "even");
            },

            "nth" : function(c, a){
                return c[a-1] || [];
            },

            "first" : function(c){
                return c[0] || [];
            },

            "last" : function(c){
                return c[c.length-1] || [];
            },

            "has" : function(c, ss){
                var s = Ext.DomQuery.select,
                	r = [], ri = -1;
                for(var i = 0, ci; ci = c[i]; i++){
                    if(s(ss, ci).length > 0){
                        r[++ri] = ci;
                    }
                }
                return r;
            },

            "next" : function(c, ss){
                var is = Ext.DomQuery.is,
                	r = [], ri = -1;
                for(var i = 0, ci; ci = c[i]; i++){
                    var n = next(ci);
                    if(n && is(n, ss)){
                        r[++ri] = ci;
                    }
                }
                return r;
            },

            "prev" : function(c, ss){
                var is = Ext.DomQuery.is,
                	r = [], ri = -1;
                for(var i = 0, ci; ci = c[i]; i++){
                    var n = prev(ci);
                    if(n && is(n, ss)){
                        r[++ri] = ci;
                    }
                }
                return r;
            }
        }
    };
}();


Ext.query = Ext.DomQuery.select;

Ext.util.DelayedTask = function(fn, scope, args){
    var me = this,
    	id,    	
    	call = function(){
    		clearInterval(id);
	        id = null;
	        fn.apply(scope, args || []);
	    };
	    
    
    me.delay = function(delay, newFn, newScope, newArgs){
        me.cancel();
        fn = newFn || fn;
        scope = newScope || scope;
        args = newArgs || args;
        id = setInterval(call, delay);
    };

    
    me.cancel = function(){
        if(id){
            clearInterval(id);
            id = null;
        }
    };
};(function(){

var EXTUTIL = Ext.util,
    EACH = Ext.each,
    TRUE = true,
    FALSE = false;

EXTUTIL.Observable = function(){
    
    var me = this, e = me.events;
    if(me.listeners){
        me.on(me.listeners);
        delete me.listeners;
    }
    me.events = e || {};
};

EXTUTIL.Observable.prototype = {
    
    filterOptRe : /^(?:scope|delay|buffer|single)$/,

    
    fireEvent : function(){
        var a = Array.prototype.slice.call(arguments, 0),
            ename = a[0].toLowerCase(),
            me = this,
            ret = TRUE,
            ce = me.events[ename],
            cc,
            q,
            c;
        if (me.eventsSuspended === TRUE) {
            if (q = me.eventQueue) {
                q.push(a);
            }
        }
        else if(typeof ce == 'object') {
            if (ce.bubble){
                if(ce.fire.apply(ce, a.slice(1)) === FALSE) {
                    return FALSE;
                }
                c = me.getBubbleTarget && me.getBubbleTarget();
                if(c && c.enableBubble) {
                    cc = c.events[ename];
                    if(!cc || typeof cc != 'object' || !cc.bubble) {
                        c.enableBubble(ename);
                    }
                    return c.fireEvent.apply(c, a);
                }
            }
            else {
                a.shift();
                ret = ce.fire.apply(ce, a);
            }
        }
        return ret;
    },

    
    addListener : function(eventName, fn, scope, o){
        var me = this,
            e,
            oe,
            isF,
        ce;
        if (typeof eventName == 'object') {
            o = eventName;
            for (e in o){
                oe = o[e];
                if (!me.filterOptRe.test(e)) {
                    me.addListener(e, oe.fn || oe, oe.scope || o.scope, oe.fn ? oe : o);
                }
            }
        } else {
            eventName = eventName.toLowerCase();
            ce = me.events[eventName] || TRUE;
            if (typeof ce == 'boolean') {
                me.events[eventName] = ce = new EXTUTIL.Event(me, eventName);
            }
            ce.addListener(fn, scope, typeof o == 'object' ? o : {});
        }
    },

    
    removeListener : function(eventName, fn, scope){
        var ce = this.events[eventName.toLowerCase()];
        if (typeof ce == 'object') {
            ce.removeListener(fn, scope);
        }
    },

    
    purgeListeners : function(){
        var events = this.events,
            evt,
            key;
        for(key in events){
            evt = events[key];
            if(typeof evt == 'object'){
                evt.clearListeners();
            }
        }
    },

    
    addEvents : function(o){
        var me = this;
        me.events = me.events || {};
        if (typeof o == 'string') {
            var a = arguments,
                i = a.length;
            while(i--) {
                me.events[a[i]] = me.events[a[i]] || TRUE;
            }
        } else {
            Ext.applyIf(me.events, o);
        }
    },

    
    hasListener : function(eventName){
        var e = this.events[eventName.toLowerCase()];
        return typeof e == 'object' && e.listeners.length > 0;
    },

    
    suspendEvents : function(queueSuspended){
        this.eventsSuspended = TRUE;
        if(queueSuspended && !this.eventQueue){
            this.eventQueue = [];
        }
    },

    
    resumeEvents : function(){
        var me = this,
            queued = me.eventQueue || [];
        me.eventsSuspended = FALSE;
        delete me.eventQueue;
        EACH(queued, function(e) {
            me.fireEvent.apply(me, e);
        });
    }
};

var OBSERVABLE = EXTUTIL.Observable.prototype;

OBSERVABLE.on = OBSERVABLE.addListener;

OBSERVABLE.un = OBSERVABLE.removeListener;


EXTUTIL.Observable.releaseCapture = function(o){
    o.fireEvent = OBSERVABLE.fireEvent;
};

function createTargeted(h, o, scope){
    return function(){
        if(o.target == arguments[0]){
            h.apply(scope, Array.prototype.slice.call(arguments, 0));
        }
    };
};

function createBuffered(h, o, l, scope){
    l.task = new EXTUTIL.DelayedTask();
    return function(){
        l.task.delay(o.buffer, h, scope, Array.prototype.slice.call(arguments, 0));
    };
};

function createSingle(h, e, fn, scope){
    return function(){
        e.removeListener(fn, scope);
        return h.apply(scope, arguments);
    };
};

function createDelayed(h, o, l, scope){
    return function(){
        var task = new EXTUTIL.DelayedTask();
        if(!l.tasks) {
            l.tasks = [];
        }
        l.tasks.push(task);
        task.delay(o.delay || 10, h, scope, Array.prototype.slice.call(arguments, 0));
    };
};

EXTUTIL.Event = function(obj, name){
    this.name = name;
    this.obj = obj;
    this.listeners = [];
};

EXTUTIL.Event.prototype = {
    addListener : function(fn, scope, options){
        var me = this,
            l;
        scope = scope || me.obj;
        if(!me.isListening(fn, scope)){
            l = me.createListener(fn, scope, options);
            if(me.firing){ 
                me.listeners = me.listeners.slice(0);
            }
            me.listeners.push(l);
        }
    },

    createListener: function(fn, scope, o){
        o = o || {}, scope = scope || this.obj;
        var l = {
            fn: fn,
            scope: scope,
            options: o
        }, h = fn;
        if(o.target){
            h = createTargeted(h, o, scope);
        }
        if(o.delay){
            h = createDelayed(h, o, l, scope);
        }
        if(o.single){
            h = createSingle(h, this, fn, scope);
        }
        if(o.buffer){
            h = createBuffered(h, o, l, scope);
        }
        l.fireFn = h;
        return l;
    },

    findListener : function(fn, scope){
        var list = this.listeners,
            i = list.length,
            l;

        scope = scope || this.obj;
        while(i--){
            l = list[i];
            if(l){
                if(l.fn == fn && l.scope == scope){
                    return i;
                }
            }
        }
        return -1;
    },

    isListening : function(fn, scope){
        return this.findListener(fn, scope) != -1;
    },

    removeListener : function(fn, scope){
        var index,
            l,
            k,
            me = this,
            ret = FALSE;
        if((index = me.findListener(fn, scope)) != -1){
            if (me.firing) {
                me.listeners = me.listeners.slice(0);
            }
            l = me.listeners[index];
            if(l.task) {
                l.task.cancel();
                delete l.task;
            }
            k = l.tasks && l.tasks.length;
            if(k) {
                while(k--) {
                    l.tasks[k].cancel();
                }
                delete l.tasks;
            }
            me.listeners.splice(index, 1);
            ret = TRUE;
        }
        return ret;
    },

    
    clearListeners : function(){
        var me = this,
            l = me.listeners,
            i = l.length;
        while(i--) {
            me.removeListener(l[i].fn, l[i].scope);
        }
    },

    fire : function(){
        var me = this,
            listeners = me.listeners,
            len = listeners.length,
            i = 0,
            l;

        if(len > 0){
            me.firing = TRUE;
            var args = Array.prototype.slice.call(arguments, 0);
            for (; i < len; i++) {
                l = listeners[i];
                if(l && l.fireFn.apply(l.scope || me.obj || window, args) === FALSE) {
                    return (me.firing = FALSE);
                }
            }
        }
        me.firing = FALSE;
        return TRUE;
    }

};
})();

Ext.apply(Ext.util.Observable.prototype, function(){
    
    
    
    function getMethodEvent(method){
        var e = (this.methodEvents = this.methodEvents ||
        {})[method], returnValue, v, cancel, obj = this;

        if (!e) {
            this.methodEvents[method] = e = {};
            e.originalFn = this[method];
            e.methodName = method;
            e.before = [];
            e.after = [];

            var makeCall = function(fn, scope, args){
                if((v = fn.apply(scope || obj, args)) !== undefined){
                    if (typeof v == 'object') {
                        if(v.returnValue !== undefined){
                            returnValue = v.returnValue;
                        }else{
                            returnValue = v;
                        }
                        cancel = !!v.cancel;
                    }
                    else
                        if (v === false) {
                            cancel = true;
                        }
                        else {
                            returnValue = v;
                        }
                }
            };

            this[method] = function(){
                var args = Array.prototype.slice.call(arguments, 0),
                    b;
                returnValue = v = undefined;
                cancel = false;

                for(var i = 0, len = e.before.length; i < len; i++){
                    b = e.before[i];
                    makeCall(b.fn, b.scope, args);
                    if (cancel) {
                        return returnValue;
                    }
                }

                if((v = e.originalFn.apply(obj, args)) !== undefined){
                    returnValue = v;
                }

                for(var i = 0, len = e.after.length; i < len; i++){
                    b = e.after[i];
                    makeCall(b.fn, b.scope, args);
                    if (cancel) {
                        return returnValue;
                    }
                }
                return returnValue;
            };
        }
        return e;
    }

    return {
        
        
        
        beforeMethod : function(method, fn, scope){
            getMethodEvent.call(this, method).before.push({
                fn: fn,
                scope: scope
            });
        },

        
        afterMethod : function(method, fn, scope){
            getMethodEvent.call(this, method).after.push({
                fn: fn,
                scope: scope
            });
        },

        removeMethodListener: function(method, fn, scope){
            var e = this.getMethodEvent(method);
            for(var i = 0, len = e.before.length; i < len; i++){
                if(e.before[i].fn == fn && e.before[i].scope == scope){
                    e.before.splice(i, 1);
                    return;
                }
            }
            for(var i = 0, len = e.after.length; i < len; i++){
                if(e.after[i].fn == fn && e.after[i].scope == scope){
                    e.after.splice(i, 1);
                    return;
                }
            }
        },

        
        relayEvents : function(o, events){
            var me = this;
            function createHandler(ename){
                return function(){
                    return me.fireEvent.apply(me, [ename].concat(Array.prototype.slice.call(arguments, 0)));
                };
            }
            for(var i = 0, len = events.length; i < len; i++){
                var ename = events[i];
                me.events[ename] = me.events[ename] || true;
                o.on(ename, createHandler(ename), me);
            }
        },

        
        enableBubble : function(events){
            var me = this;
            if(!Ext.isEmpty(events)){
                events = Ext.isArray(events) ? events : Array.prototype.slice.call(arguments, 0);
                for(var i = 0, len = events.length; i < len; i++){
                    var ename = events[i];
                    ename = ename.toLowerCase();
                    var ce = me.events[ename] || true;
                    if (typeof ce == 'boolean') {
                        ce = new Ext.util.Event(me, ename);
                        me.events[ename] = ce;
                    }
                    ce.bubble = true;
                }
            }
        }
    };
}());



Ext.util.Observable.capture = function(o, fn, scope){
    o.fireEvent = o.fireEvent.createInterceptor(fn, scope);
};



Ext.util.Observable.observeClass = function(c, listeners){
    if(c){
      if(!c.fireEvent){
          Ext.apply(c, new Ext.util.Observable());
          Ext.util.Observable.capture(c.prototype, c.fireEvent, c);
      }
      if(typeof listeners == 'object'){
          c.on(listeners);
      }
      return c;
   }
};


Ext.EventManager = function(){
    var docReadyEvent,
        docReadyProcId,
        docReadyState = false,
        DETECT_NATIVE = Ext.isGecko || Ext.isWebKit || Ext.isSafari,
        E = Ext.lib.Event,
        D = Ext.lib.Dom,
        DOC = document,
        WINDOW = window,
        DOMCONTENTLOADED = "DOMContentLoaded",
        COMPLETE = 'complete',
        propRe = /^(?:scope|delay|buffer|single|stopEvent|preventDefault|stopPropagation|normalized|args|delegate)$/,
        
        specialElCache = [];

     function getId(el){
        var id = false,
            i = 0,
            len = specialElCache.length,
            id = false,
            skip = false,
            o;
        if(el){
            if(el.getElementById || el.navigator){
                
                for(; i < len; ++i){
                    o = specialElCache[i];
                    if(o.el === el){
                        id = o.id;
                        break;
                    }
                }
                if(!id){
                    
                    id = Ext.id(el);
                    specialElCache.push({
                        id: id,
                        el: el
                    });
                    skip = true;
                }
            }else{
                id = Ext.id(el);
            }
            if(!Ext.elCache[id]){
                Ext.Element.addToCache(new Ext.Element(el), id);
                if(skip){
                    Ext.elCache[id].skipGC = true;
                }
            }
        }
        return id;
     };

    
    function addListener(el, ename, fn, task, wrap, scope){
        el = Ext.getDom(el);
        var id = getId(el),
            es = Ext.elCache[id].events,
            wfn;

        wfn = E.on(el, ename, wrap);
        es[ename] = es[ename] || [];

        
        es[ename].push([fn, wrap, scope, wfn, task]);

        
        

        
        if(el.addEventListener && ename == "mousewheel"){
            var args = ["DOMMouseScroll", wrap, false];
            el.addEventListener.apply(el, args);
            Ext.EventManager.addListener(WINDOW, 'unload', function(){
                el.removeEventListener.apply(el, args);
            });
        }

        
        if(el == DOC && ename == "mousedown"){
            Ext.EventManager.stoppedMouseDownEvent.addListener(wrap);
        }
    };

    function doScrollChk(){
        
        if(window != top){
            return false;
        }

        try{
            DOC.documentElement.doScroll('left');
        }catch(e){
             return false;
        }

        fireDocReady();
        return true;
    }
    
    function checkReadyState(e){

        if(Ext.isIE && doScrollChk()){
            return true;
        }
        if(DOC.readyState == COMPLETE){
            fireDocReady();
            return true;
        }
        docReadyState || (docReadyProcId = setTimeout(arguments.callee, 2));
        return false;
    }

    var styles;
    function checkStyleSheets(e){
        styles || (styles = Ext.query('style, link[rel=stylesheet]'));
        if(styles.length == DOC.styleSheets.length){
            fireDocReady();
            return true;
        }
        docReadyState || (docReadyProcId = setTimeout(arguments.callee, 2));
        return false;
    }

    function OperaDOMContentLoaded(e){
        DOC.removeEventListener(DOMCONTENTLOADED, arguments.callee, false);
        checkStyleSheets();
    }

    function fireDocReady(e){
        if(!docReadyState){
            docReadyState = true; 

            if(docReadyProcId){
                clearTimeout(docReadyProcId);
            }
            if(DETECT_NATIVE) {
                DOC.removeEventListener(DOMCONTENTLOADED, fireDocReady, false);
            }
            if(Ext.isIE && checkReadyState.bindIE){  
                DOC.detachEvent('onreadystatechange', checkReadyState);
            }
            E.un(WINDOW, "load", arguments.callee);
        }
        if(docReadyEvent && !Ext.isReady){
            Ext.isReady = true;
            docReadyEvent.fire();
            docReadyEvent.listeners = [];
        }

    };

    function initDocReady(){
        docReadyEvent || (docReadyEvent = new Ext.util.Event());
        if (DETECT_NATIVE) {
            DOC.addEventListener(DOMCONTENTLOADED, fireDocReady, false);
        }
        
        if (Ext.isIE){
            
            
            if(!checkReadyState()){
                checkReadyState.bindIE = true;
                DOC.attachEvent('onreadystatechange', checkReadyState);
            }

        }else if(Ext.isOpera ){
            

            
            (DOC.readyState == COMPLETE && checkStyleSheets()) ||
                DOC.addEventListener(DOMCONTENTLOADED, OperaDOMContentLoaded, false);

        }else if (Ext.isWebKit){
            
            checkReadyState();
        }
        
        E.on(WINDOW, "load", fireDocReady);
    };

    function createTargeted(h, o){
        return function(){
            var args = Ext.toArray(arguments);
            if(o.target == Ext.EventObject.setEvent(args[0]).target){
                h.apply(this, args);
            }
        };
    };

    function createBuffered(h, o, task){
        return function(e){
            
            task.delay(o.buffer, h, null, [new Ext.EventObjectImpl(e)]);
        };
    };

    function createSingle(h, el, ename, fn, scope){
        return function(e){
            Ext.EventManager.removeListener(el, ename, fn, scope);
            h(e);
        };
    };

    function createDelayed(h, o, fn){
        return function(e){
            var task = new Ext.util.DelayedTask(h);
            if(!fn.tasks) {
                fn.tasks = [];
            }
            fn.tasks.push(task);
            task.delay(o.delay || 10, h, null, [new Ext.EventObjectImpl(e)]);
        };
    };

    function listen(element, ename, opt, fn, scope){
        var o = (!opt || typeof opt == "boolean") ? {} : opt,
            el = Ext.getDom(element), task;

        fn = fn || o.fn;
        scope = scope || o.scope;

        if(!el){
            throw "Error listening for \"" + ename + '\". Element "' + element + '" doesn\'t exist.';
        }
        function h(e){
            
            if(!Ext){
                return;
            }
            e = Ext.EventObject.setEvent(e);
            var t;
            if (o.delegate) {
                if(!(t = e.getTarget(o.delegate, el))){
                    return;
                }
            } else {
                t = e.target;
            }
            if (o.stopEvent) {
                e.stopEvent();
            }
            if (o.preventDefault) {
               e.preventDefault();
            }
            if (o.stopPropagation) {
                e.stopPropagation();
            }
            if (o.normalized) {
                e = e.browserEvent;
            }

            fn.call(scope || el, e, t, o);
        };
        if(o.target){
            h = createTargeted(h, o);
        }
        if(o.delay){
            h = createDelayed(h, o, fn);
        }
        if(o.single){
            h = createSingle(h, el, ename, fn, scope);
        }
        if(o.buffer){
            task = new Ext.util.DelayedTask(h);
            h = createBuffered(h, o, task);
        }

        addListener(el, ename, fn, task, h, scope);
        return h;
    };

    var pub = {
        
        addListener : function(element, eventName, fn, scope, options){
            if(typeof eventName == 'object'){
                var o = eventName, e, val;
                for(e in o){
                    val = o[e];
                    if(!propRe.test(e)){
                        if(Ext.isFunction(val)){
                            
                            listen(element, e, o, val, o.scope);
                        }else{
                            
                            listen(element, e, val);
                        }
                    }
                }
            } else {
                listen(element, eventName, options, fn, scope);
            }
        },

        
        removeListener : function(el, eventName, fn, scope){
            el = Ext.getDom(el);
            var id = getId(el),
                f = el && (Ext.elCache[id].events)[eventName] || [],
                wrap, i, l, k, len, fnc;

            for (i = 0, len = f.length; i < len; i++) {

                
                if (Ext.isArray(fnc = f[i]) && fnc[0] == fn && (!scope || fnc[2] == scope)) {
                    if(fnc[4]) {
                        fnc[4].cancel();
                    }
                    k = fn.tasks && fn.tasks.length;
                    if(k) {
                        while(k--) {
                            fn.tasks[k].cancel();
                        }
                        delete fn.tasks;
                    }
                    wrap = fnc[1];
                    E.un(el, eventName, E.extAdapter ? fnc[3] : wrap);

                    
                    if(wrap && el.addEventListener && eventName == "mousewheel"){
                        el.removeEventListener("DOMMouseScroll", wrap, false);
                    }

                    
                    if(wrap && el == DOC && eventName == "mousedown"){
                        Ext.EventManager.stoppedMouseDownEvent.removeListener(wrap);
                    }

                    f.splice(i, 1);
                    if (f.length === 0) {
                        delete Ext.elCache[id].events[eventName];
                    }
                    for (k in Ext.elCache[id].events) {
                        return false;
                    }
                    Ext.elCache[id].events = {};
                    return false;
                }
            }
        },

        
        removeAll : function(el){
            el = Ext.getDom(el);
            var id = getId(el),
                ec = Ext.elCache[id] || {},
                es = ec.events || {},
                f, i, len, ename, fn, k, wrap;

            for(ename in es){
                if(es.hasOwnProperty(ename)){
                    f = es[ename];
                    
                    for (i = 0, len = f.length; i < len; i++) {
                        fn = f[i];
                        if(fn[4]) {
                            fn[4].cancel();
                        }
                        if(fn[0].tasks && (k = fn[0].tasks.length)) {
                            while(k--) {
                                fn[0].tasks[k].cancel();
                            }
                            delete fn.tasks;
                        }
                        wrap =  fn[1];
                        E.un(el, ename, E.extAdapter ? fn[3] : wrap);

                        
                        if(el.addEventListener && wrap && ename == "mousewheel"){
                            el.removeEventListener("DOMMouseScroll", wrap, false);
                        }

                        
                        if(wrap && el == DOC &&  ename == "mousedown"){
                            Ext.EventManager.stoppedMouseDownEvent.removeListener(wrap);
                        }
                    }
                }
            }
            if (Ext.elCache[id]) {
                Ext.elCache[id].events = {};
            }
        },

        getListeners : function(el, eventName) {
            el = Ext.getDom(el);
            var id = getId(el),
                ec = Ext.elCache[id] || {},
                es = ec.events || {},
                results = [];
            if (es && es[eventName]) {
                return es[eventName];
            } else {
                return null;
            }
        },

        purgeElement : function(el, recurse, eventName) {
            el = Ext.getDom(el);
            var id = getId(el),
                ec = Ext.elCache[id] || {},
                es = ec.events || {},
                i, f, len;
            if (eventName) {
                if (es && es.hasOwnProperty(eventName)) {
                    f = es[eventName];
                    for (i = 0, len = f.length; i < len; i++) {
                        Ext.EventManager.removeListener(el, eventName, f[i][0]);
                    }
                }
            } else {
                Ext.EventManager.removeAll(el);
            }
            if (recurse && el && el.childNodes) {
                for (i = 0, len = el.childNodes.length; i < len; i++) {
                    Ext.EventManager.purgeElement(el.childNodes[i], recurse, eventName);
                }
            }
        },

        _unload : function() {
            var el;
            for (el in Ext.elCache) {
                Ext.EventManager.removeAll(el);
            }
            delete Ext.elCache;
            delete Ext.Element._flyweights;

            
            var c,
                conn,
                tid,
                ajax = Ext.lib.Ajax;
            (typeof ajax.conn == 'object') ? conn = ajax.conn : conn = {};
            for (tid in conn) {
                c = conn[tid];
                if (c) {
                    ajax.abort({conn: c, tId: tid});
                }
            }
        },
        
        onDocumentReady : function(fn, scope, options){
            if(Ext.isReady){ 
                docReadyEvent || (docReadyEvent = new Ext.util.Event());
                docReadyEvent.addListener(fn, scope, options);
                docReadyEvent.fire();
                docReadyEvent.listeners = [];
            }else{
                if(!docReadyEvent){
                    initDocReady();
                }
                options = options || {};
                options.delay = options.delay || 1;
                docReadyEvent.addListener(fn, scope, options);
            }
        },

        
        fireDocReady  : fireDocReady
    };
     
    pub.on = pub.addListener;
    
    pub.un = pub.removeListener;

    pub.stoppedMouseDownEvent = new Ext.util.Event();
    return pub;
}();

Ext.onReady = Ext.EventManager.onDocumentReady;



(function(){

    var initExtCss = function(){
        
        var bd = document.body || document.getElementsByTagName('body')[0];
        if(!bd){ return false; }
        var cls = [' ',
                Ext.isIE ? "ext-ie " + (Ext.isIE6 ? 'ext-ie6' : (Ext.isIE7 ? 'ext-ie7' : 'ext-ie8'))
                : Ext.isGecko ? "ext-gecko " + (Ext.isGecko2 ? 'ext-gecko2' : 'ext-gecko3')
                : Ext.isOpera ? "ext-opera"
                : Ext.isWebKit ? "ext-webkit" : ""];

        if(Ext.isSafari){
            cls.push("ext-safari " + (Ext.isSafari2 ? 'ext-safari2' : (Ext.isSafari3 ? 'ext-safari3' : 'ext-safari4')));
        }else if(Ext.isChrome){
            cls.push("ext-chrome");
        }

        if(Ext.isMac){
            cls.push("ext-mac");
        }
        if(Ext.isLinux){
            cls.push("ext-linux");
        }

        if(Ext.isStrict || Ext.isBorderBox){ 
            var p = bd.parentNode;
            if(p){
                p.className += Ext.isStrict ? ' ext-strict' : ' ext-border-box';
            }
        }
        bd.className += cls.join(' ');
        return true;
    }

    if(!initExtCss()){
        Ext.onReady(initExtCss);
    }
})();



Ext.EventObject = function(){
    var E = Ext.lib.Event,
        
        safariKeys = {
            3 : 13, 
            63234 : 37, 
            63235 : 39, 
            63232 : 38, 
            63233 : 40, 
            63276 : 33, 
            63277 : 34, 
            63272 : 46, 
            63273 : 36, 
            63275 : 35  
        },
        
        btnMap = Ext.isIE ? {1:0,4:1,2:2} :
                (Ext.isWebKit ? {1:0,2:1,3:2} : {0:0,1:1,2:2});

    Ext.EventObjectImpl = function(e){
        if(e){
            this.setEvent(e.browserEvent || e);
        }
    };

    Ext.EventObjectImpl.prototype = {
           
        setEvent : function(e){
            var me = this;
            if(e == me || (e && e.browserEvent)){ 
                return e;
            }
            me.browserEvent = e;
            if(e){
                
                me.button = e.button ? btnMap[e.button] : (e.which ? e.which - 1 : -1);
                if(e.type == 'click' && me.button == -1){
                    me.button = 0;
                }
                me.type = e.type;
                me.shiftKey = e.shiftKey;
                
                me.ctrlKey = e.ctrlKey || e.metaKey || false;
                me.altKey = e.altKey;
                
                me.keyCode = e.keyCode;
                me.charCode = e.charCode;
                
                me.target = E.getTarget(e);
                
                me.xy = E.getXY(e);
            }else{
                me.button = -1;
                me.shiftKey = false;
                me.ctrlKey = false;
                me.altKey = false;
                me.keyCode = 0;
                me.charCode = 0;
                me.target = null;
                me.xy = [0, 0];
            }
            return me;
        },

        
        stopEvent : function(){
            var me = this;
            if(me.browserEvent){
                if(me.browserEvent.type == 'mousedown'){
                    Ext.EventManager.stoppedMouseDownEvent.fire(me);
                }
                E.stopEvent(me.browserEvent);
            }
        },

        
        preventDefault : function(){
            if(this.browserEvent){
                E.preventDefault(this.browserEvent);
            }
        },

        
        stopPropagation : function(){
            var me = this;
            if(me.browserEvent){
                if(me.browserEvent.type == 'mousedown'){
                    Ext.EventManager.stoppedMouseDownEvent.fire(me);
                }
                E.stopPropagation(me.browserEvent);
            }
        },

        
        getCharCode : function(){
            return this.charCode || this.keyCode;
        },

        
        getKey : function(){
            return this.normalizeKey(this.keyCode || this.charCode)
        },

        
        normalizeKey: function(k){
            return Ext.isSafari ? (safariKeys[k] || k) : k;
        },

        
        getPageX : function(){
            return this.xy[0];
        },

        
        getPageY : function(){
            return this.xy[1];
        },

        
        getXY : function(){
            return this.xy;
        },

        
        getTarget : function(selector, maxDepth, returnEl){
            return selector ? Ext.fly(this.target).findParent(selector, maxDepth, returnEl) : (returnEl ? Ext.get(this.target) : this.target);
        },

        
        getRelatedTarget : function(){
            return this.browserEvent ? E.getRelatedTarget(this.browserEvent) : null;
        },

        
        getWheelDelta : function(){
            var e = this.browserEvent;
            var delta = 0;
            if(e.wheelDelta){ 
                delta = e.wheelDelta/120;
            }else if(e.detail){ 
                delta = -e.detail/3;
            }
            return delta;
        },

        
        within : function(el, related, allowEl){
            if(el){
                var t = this[related ? "getRelatedTarget" : "getTarget"]();
                return t && ((allowEl ? (t == Ext.getDom(el)) : false) || Ext.fly(el).contains(t));
            }
            return false;
        }
     };

    return new Ext.EventObjectImpl();
}();

Ext.apply(Ext.EventManager, function(){
   var resizeEvent,
       resizeTask,
       textEvent,
       textSize,
       D = Ext.lib.Dom,
       propRe = /^(?:scope|delay|buffer|single|stopEvent|preventDefault|stopPropagation|normalized|args|delegate)$/,
       curWidth = 0,
       curHeight = 0,
       
       
       
       useKeydown = Ext.isWebKit ?
                   Ext.num(navigator.userAgent.match(/AppleWebKit\/(\d+)/)[1]) >= 525 :
                   !((Ext.isGecko && !Ext.isWindows) || Ext.isOpera);

   return {
       
       doResizeEvent: function(){
           var h = D.getViewHeight(),
               w = D.getViewWidth();

            
            if(curHeight != h || curWidth != w){
               resizeEvent.fire(curWidth = w, curHeight = h);
            }
       },

       
       onWindowResize : function(fn, scope, options){
           if(!resizeEvent){
               resizeEvent = new Ext.util.Event();
               resizeTask = new Ext.util.DelayedTask(this.doResizeEvent);
               Ext.EventManager.on(window, "resize", this.fireWindowResize, this);
           }
           resizeEvent.addListener(fn, scope, options);
       },

       
       fireWindowResize : function(){
           if(resizeEvent){
               resizeTask.delay(100);
           }
       },

       
       onTextResize : function(fn, scope, options){
           if(!textEvent){
               textEvent = new Ext.util.Event();
               var textEl = new Ext.Element(document.createElement('div'));
               textEl.dom.className = 'x-text-resize';
               textEl.dom.innerHTML = 'X';
               textEl.appendTo(document.body);
               textSize = textEl.dom.offsetHeight;
               setInterval(function(){
                   if(textEl.dom.offsetHeight != textSize){
                       textEvent.fire(textSize, textSize = textEl.dom.offsetHeight);
                   }
               }, this.textResizeInterval);
           }
           textEvent.addListener(fn, scope, options);
       },

       
       removeResizeListener : function(fn, scope){
           if(resizeEvent){
               resizeEvent.removeListener(fn, scope);
           }
       },

       
       fireResize : function(){
           if(resizeEvent){
               resizeEvent.fire(D.getViewWidth(), D.getViewHeight());
           }
       },

        
       textResizeInterval : 50,

       
       ieDeferSrc : false,

       
       
       useKeydown: useKeydown
   };
}());

Ext.EventManager.on = Ext.EventManager.addListener;


Ext.apply(Ext.EventObjectImpl.prototype, {
   
   BACKSPACE: 8,
   
   TAB: 9,
   
   NUM_CENTER: 12,
   
   ENTER: 13,
   
   RETURN: 13,
   
   SHIFT: 16,
   
   CTRL: 17,
   CONTROL : 17, 
   
   ALT: 18,
   
   PAUSE: 19,
   
   CAPS_LOCK: 20,
   
   ESC: 27,
   
   SPACE: 32,
   
   PAGE_UP: 33,
   PAGEUP : 33, 
   
   PAGE_DOWN: 34,
   PAGEDOWN : 34, 
   
   END: 35,
   
   HOME: 36,
   
   LEFT: 37,
   
   UP: 38,
   
   RIGHT: 39,
   
   DOWN: 40,
   
   PRINT_SCREEN: 44,
   
   INSERT: 45,
   
   DELETE: 46,
   
   ZERO: 48,
   
   ONE: 49,
   
   TWO: 50,
   
   THREE: 51,
   
   FOUR: 52,
   
   FIVE: 53,
   
   SIX: 54,
   
   SEVEN: 55,
   
   EIGHT: 56,
   
   NINE: 57,
   
   A: 65,
   
   B: 66,
   
   C: 67,
   
   D: 68,
   
   E: 69,
   
   F: 70,
   
   G: 71,
   
   H: 72,
   
   I: 73,
   
   J: 74,
   
   K: 75,
   
   L: 76,
   
   M: 77,
   
   N: 78,
   
   O: 79,
   
   P: 80,
   
   Q: 81,
   
   R: 82,
   
   S: 83,
   
   T: 84,
   
   U: 85,
   
   V: 86,
   
   W: 87,
   
   X: 88,
   
   Y: 89,
   
   Z: 90,
   
   CONTEXT_MENU: 93,
   
   NUM_ZERO: 96,
   
   NUM_ONE: 97,
   
   NUM_TWO: 98,
   
   NUM_THREE: 99,
   
   NUM_FOUR: 100,
   
   NUM_FIVE: 101,
   
   NUM_SIX: 102,
   
   NUM_SEVEN: 103,
   
   NUM_EIGHT: 104,
   
   NUM_NINE: 105,
   
   NUM_MULTIPLY: 106,
   
   NUM_PLUS: 107,
   
   NUM_MINUS: 109,
   
   NUM_PERIOD: 110,
   
   NUM_DIVISION: 111,
   
   F1: 112,
   
   F2: 113,
   
   F3: 114,
   
   F4: 115,
   
   F5: 116,
   
   F6: 117,
   
   F7: 118,
   
   F8: 119,
   
   F9: 120,
   
   F10: 121,
   
   F11: 122,
   
   F12: 123,

   
   isNavKeyPress : function(){
       var me = this,
           k = this.normalizeKey(me.keyCode);
       return (k >= 33 && k <= 40) ||  
       k == me.RETURN ||
       k == me.TAB ||
       k == me.ESC;
   },

   isSpecialKey : function(){
       var k = this.normalizeKey(this.keyCode);
       return (this.type == 'keypress' && this.ctrlKey) ||
       this.isNavKeyPress() ||
       (k == this.BACKSPACE) || 
       (k >= 16 && k <= 20) || 
       (k >= 44 && k <= 46);   
   },

   getPoint : function(){
       return new Ext.lib.Point(this.xy[0], this.xy[1]);
   },

   
   hasModifier : function(){
       return ((this.ctrlKey || this.altKey) || this.shiftKey);
   }
});
(function(){
var DOC = document;

Ext.Element = function(element, forceNew){
    var dom = typeof element == "string" ?
              DOC.getElementById(element) : element,
        id;

    if(!dom) return null;

    id = dom.id;

    if(!forceNew && id && Ext.elCache[id]){ 
        return Ext.elCache[id].el;
    }

    
    this.dom = dom;

    
    this.id = id || Ext.id(dom);
};

var D = Ext.lib.Dom,
    DH = Ext.DomHelper,
    E = Ext.lib.Event,
    A = Ext.lib.Anim,
    El = Ext.Element,
    EC = Ext.elCache;

El.prototype = {
    
    set : function(o, useSet){
        var el = this.dom,
            attr,
            val,
            useSet = (useSet !== false) && !!el.setAttribute;

        for(attr in o){
            if (o.hasOwnProperty(attr)) {
                val = o[attr];
                if (attr == 'style') {
                    DH.applyStyles(el, val);
                } else if (attr == 'cls') {
                    el.className = val;
                } else if (useSet) {
                    el.setAttribute(attr, val);
                } else {
                    el[attr] = val;
                }
            }
        }
        return this;
    },


    
    
    
    
    
    
    
    
    
    


    
    
    



    
    
    
    
    
    


    
    
    
    
    
    


    
    
    


    
    
    
    
    
    
    

    
    defaultUnit : "px",

    
    is : function(simpleSelector){
        return Ext.DomQuery.is(this.dom, simpleSelector);
    },

    
    focus : function(defer,  dom) {
        var me = this,
            dom = dom || me.dom;
        try{
            if(Number(defer)){
                me.focus.defer(defer, null, [null, dom]);
            }else{
                dom.focus();
            }
        }catch(e){}
        return me;
    },

    
    blur : function() {
        try{
            this.dom.blur();
        }catch(e){}
        return this;
    },

    
    getValue : function(asNumber){
        var val = this.dom.value;
        return asNumber ? parseInt(val, 10) : val;
    },

    
    addListener : function(eventName, fn, scope, options){
        Ext.EventManager.on(this.dom,  eventName, fn, scope || this, options);
        return this;
    },

    
    removeListener : function(eventName, fn, scope){
        Ext.EventManager.removeListener(this.dom,  eventName, fn, scope || this);
        return this;
    },

    
    removeAllListeners : function(){
        Ext.EventManager.removeAll(this.dom);
        return this;
    },

    
    purgeAllListeners : function() {
        Ext.EventManager.purgeElement(this, true);
        return this;
    },
    
    addUnits : function(size){
        if(size === "" || size == "auto" || size === undefined){
            size = size || '';
        } else if(!isNaN(size) || !unitPattern.test(size)){
            size = size + (this.defaultUnit || 'px');
        }
        return size;
    },

    
    load : function(url, params, cb){
        Ext.Ajax.request(Ext.apply({
            params: params,
            url: url.url || url,
            callback: cb,
            el: this.dom,
            indicatorText: url.indicatorText || ''
        }, Ext.isObject(url) ? url : {}));
        return this;
    },

    
    isBorderBox : function(){
        return noBoxAdjust[(this.dom.tagName || "").toLowerCase()] || Ext.isBorderBox;
    },

    
    remove : function(){
        var me = this,
            dom = me.dom;

        if (dom) {
            delete me.dom;
            Ext.removeNode(dom);
        }
    },

    
    hover : function(overFn, outFn, scope, options){
        var me = this;
        me.on('mouseenter', overFn, scope || me.dom, options);
        me.on('mouseleave', outFn, scope || me.dom, options);
        return me;
    },

    
    contains : function(el){
        return !el ? false : Ext.lib.Dom.isAncestor(this.dom, el.dom ? el.dom : el);
    },

    
    getAttributeNS : function(ns, name){
        return this.getAttribute(name, ns);
    },

    
    getAttribute : Ext.isIE ? function(name, ns){
        var d = this.dom,
            type = typeof d[ns + ":" + name];

        if(['undefined', 'unknown'].indexOf(type) == -1){
            return d[ns + ":" + name];
        }
        return d[name];
    } : function(name, ns){
        var d = this.dom;
        return d.getAttributeNS(ns, name) || d.getAttribute(ns + ":" + name) || d.getAttribute(name) || d[name];
    },

    
    update : function(html) {
        if (this.dom) {
            this.dom.innerHTML = html;
        }
        return this;
    }
};

var ep = El.prototype;

El.addMethods = function(o){
   Ext.apply(ep, o);
};


ep.on = ep.addListener;


ep.un = ep.removeListener;


ep.autoBoxAdjust = true;


var unitPattern = /\d+(px|em|%|en|ex|pt|in|cm|mm|pc)$/i,
    docEl;




El.get = function(el){
    var ex,
        elm,
        id;
    if(!el){ return null; }
    if (typeof el == "string") { 
        if (!(elm = DOC.getElementById(el))) {
            return null;
        }
        if (EC[el] && EC[el].el) {
            ex = EC[el].el;
            ex.dom = elm;
        } else {
            ex = El.addToCache(new El(elm));
        }
        return ex;
    } else if (el.tagName) { 
        if(!(id = el.id)){
            id = Ext.id(el);
        }
        if (EC[id] && EC[id].el) {
            ex = EC[id].el;
            ex.dom = el;
        } else {
            ex = El.addToCache(new El(el));
        }
        return ex;
    } else if (el instanceof El) {
        if(el != docEl){
            
            

            
            if (Ext.isIE && (el.id == undefined || el.id == '')) {
                el.dom = el.dom;
            } else {
                el.dom = DOC.getElementById(el.id) || el.dom;
            }
        }
        return el;
    } else if(el.isComposite) {
        return el;
    } else if(Ext.isArray(el)) {
        return El.select(el);
    } else if(el == DOC) {
        
        if(!docEl){
            var f = function(){};
            f.prototype = El.prototype;
            docEl = new f();
            docEl.dom = DOC;
        }
        return docEl;
    }
    return null;
};

El.addToCache = function(el, id){
    id = id || el.id;
    EC[id] = {
        el:  el,
        data: {},
        events: {}
    };
    return el;
};


El.data = function(el, key, value){
    el = El.get(el);
    if (!el) {
        return null;
    }
    var c = EC[el.id].data;
    if(arguments.length == 2){
        return c[key];
    }else{
        return (c[key] = value);
    }
};




function garbageCollect(){
    if(!Ext.enableGarbageCollector){
        clearInterval(El.collectorThreadId);
    } else {
        var eid,
            el,
            d,
            o;

        for(eid in EC){
            o = EC[eid];
            if(o.skipGC){
                continue;
            }
            el = o.el;
            d = el.dom;
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            if(!d || !d.parentNode || (!d.offsetParent && !DOC.getElementById(eid))){
                if(Ext.enableListenerCollection){
                    Ext.EventManager.removeAll(d);
                }
                delete EC[eid];
            }
        }
        
        if (Ext.isIE) {
            var t = {};
            for (eid in EC) {
                t[eid] = EC[eid];
            }
            EC = Ext.elCache = t;
        }
    }
}
El.collectorThreadId = setInterval(garbageCollect, 30000);

var flyFn = function(){};
flyFn.prototype = El.prototype;


El.Flyweight = function(dom){
    this.dom = dom;
};

El.Flyweight.prototype = new flyFn();
El.Flyweight.prototype.isFlyweight = true;
El._flyweights = {};


El.fly = function(el, named){
    var ret = null;
    named = named || '_global';

    if (el = Ext.getDom(el)) {
        (El._flyweights[named] = El._flyweights[named] || new El.Flyweight()).dom = el;
        ret = El._flyweights[named];
    }
    return ret;
};


Ext.get = El.get;


Ext.fly = El.fly;


var noBoxAdjust = Ext.isStrict ? {
    select:1
} : {
    input:1, select:1, textarea:1
};
if(Ext.isIE || Ext.isGecko){
    noBoxAdjust['button'] = 1;
}

})();

Ext.Element.addMethods({
    
    swallowEvent : function(eventName, preventDefault){
        var me = this;
        function fn(e){
            e.stopPropagation();
            if(preventDefault){
                e.preventDefault();
            }
        }
        if(Ext.isArray(eventName)){
            Ext.each(eventName, function(e) {
                 me.on(e, fn);
            });
            return me;
        }
        me.on(eventName, fn);
        return me;
    },

    
    relayEvent : function(eventName, observable){
        this.on(eventName, function(e){
            observable.fireEvent(eventName, e);
        });
    },

    
    clean : function(forceReclean){
        var me = this,
            dom = me.dom,
            n = dom.firstChild,
            ni = -1;

        if(Ext.Element.data(dom, 'isCleaned') && forceReclean !== true){
            return me;
        }

        while(n){
            var nx = n.nextSibling;
            if(n.nodeType == 3 && !/\S/.test(n.nodeValue)){
                dom.removeChild(n);
            }else{
                n.nodeIndex = ++ni;
            }
            n = nx;
        }
        Ext.Element.data(dom, 'isCleaned', true);
        return me;
    },

    
    load : function(){
        var um = this.getUpdater();
        um.update.apply(um, arguments);
        return this;
    },

    
    getUpdater : function(){
        return this.updateManager || (this.updateManager = new Ext.Updater(this));
    },

    
    update : function(html, loadScripts, callback){
        if (!this.dom) {
            return this;
        }
        html = html || "";

        if(loadScripts !== true){
            this.dom.innerHTML = html;
            if(typeof callback == 'function'){
                callback();
            }
            return this;
        }

        var id = Ext.id(),
            dom = this.dom;

        html += '<span id="' + id + '"></span>';

        Ext.lib.Event.onAvailable(id, function(){
            var DOC = document,
                hd = DOC.getElementsByTagName("head")[0],
                re = /(?:<script([^>]*)?>)((\n|\r|.)*?)(?:<\/script>)/ig,
                srcRe = /\ssrc=([\'\"])(.*?)\1/i,
                typeRe = /\stype=([\'\"])(.*?)\1/i,
                match,
                attrs,
                srcMatch,
                typeMatch,
                el,
                s;

            while((match = re.exec(html))){
                attrs = match[1];
                srcMatch = attrs ? attrs.match(srcRe) : false;
                if(srcMatch && srcMatch[2]){
                   s = DOC.createElement("script");
                   s.src = srcMatch[2];
                   typeMatch = attrs.match(typeRe);
                   if(typeMatch && typeMatch[2]){
                       s.type = typeMatch[2];
                   }
                   hd.appendChild(s);
                }else if(match[2] && match[2].length > 0){
                    if(window.execScript) {
                       window.execScript(match[2]);
                    } else {
                       window.eval(match[2]);
                    }
                }
            }
            el = DOC.getElementById(id);
            if(el){Ext.removeNode(el);}
            if(typeof callback == 'function'){
                callback();
            }
        });
        dom.innerHTML = html.replace(/(?:<script.*?>)((\n|\r|.)*?)(?:<\/script>)/ig, "");
        return this;
    },

    
    removeAllListeners : function(){
        this.removeAnchor();
        Ext.EventManager.removeAll(this.dom);
        return this;
    },

    
    createProxy : function(config, renderTo, matchBox){
        config = (typeof config == 'object') ? config : {tag : "div", cls: config};

        var me = this,
            proxy = renderTo ? Ext.DomHelper.append(renderTo, config, true) :
                               Ext.DomHelper.insertBefore(me.dom, config, true);

        if(matchBox && me.setBox && me.getBox){ 
           proxy.setBox(me.getBox());
        }
        return proxy;
    }
});

Ext.Element.prototype.getUpdateManager = Ext.Element.prototype.getUpdater;

Ext.Element.addMethods({
    
    getAnchorXY : function(anchor, local, s){
        
        
		anchor = (anchor || "tl").toLowerCase();
        s = s || {};
        
        var me = this,        
        	vp = me.dom == document.body || me.dom == document,
        	w = s.width || vp ? Ext.lib.Dom.getViewWidth() : me.getWidth(),
        	h = s.height || vp ? Ext.lib.Dom.getViewHeight() : me.getHeight(),         	        	
        	xy,       	
        	r = Math.round,
        	o = me.getXY(),
        	scroll = me.getScroll(),
        	extraX = vp ? scroll.left : !local ? o[0] : 0,
        	extraY = vp ? scroll.top : !local ? o[1] : 0,
        	hash = {
	        	c  : [r(w * 0.5), r(h * 0.5)],
	        	t  : [r(w * 0.5), 0],
	        	l  : [0, r(h * 0.5)],
	        	r  : [w, r(h * 0.5)],
	        	b  : [r(w * 0.5), h],
	        	tl : [0, 0],	
	        	bl : [0, h],
	        	br : [w, h],
	        	tr : [w, 0]
        	};
        
        xy = hash[anchor];	
        return [xy[0] + extraX, xy[1] + extraY]; 
    },

    
    anchorTo : function(el, alignment, offsets, animate, monitorScroll, callback){        
	    var me = this,
            dom = me.dom,
            scroll = !Ext.isEmpty(monitorScroll),
            action = function(){
                Ext.fly(dom).alignTo(el, alignment, offsets, animate);
                Ext.callback(callback, Ext.fly(dom));
            },
            anchor = this.getAnchor();
            
        
        this.removeAnchor();
        Ext.apply(anchor, {
            fn: action,
            scroll: scroll
        });

        Ext.EventManager.onWindowResize(action, null);
        
        if(scroll){
            Ext.EventManager.on(window, 'scroll', action, null,
                {buffer: !isNaN(monitorScroll) ? monitorScroll : 50});
        }
        action.call(me); 
        return me;
    },
    
    
    removeAnchor : function(){
        var me = this,
            anchor = this.getAnchor();
            
        if(anchor && anchor.fn){
            Ext.EventManager.removeResizeListener(anchor.fn);
            if(anchor.scroll){
                Ext.EventManager.un(window, 'scroll', anchor.fn);
            }
            delete anchor.fn;
        }
        return me;
    },
    
    
    getAnchor : function(){
        var data = Ext.Element.data,
            dom = this.dom;
            if (!dom) {
                return;
            }
            var anchor = data(dom, '_anchor');
            
        if(!anchor){
            anchor = data(dom, '_anchor', {});
        }
        return anchor;
    },

    
    getAlignToXY : function(el, p, o){	    
        el = Ext.get(el);
        
        if(!el || !el.dom){
            throw "Element.alignToXY with an element that doesn't exist";
        }
        
        o = o || [0,0];
        p = (!p || p == "?" ? "tl-bl?" : (!/-/.test(p) && p !== "" ? "tl-" + p : p || "tl-bl")).toLowerCase();       
                
        var me = this,
        	d = me.dom,
        	a1,
        	a2,
        	x,
        	y,
        	
        	w,
        	h,
        	r,
        	dw = Ext.lib.Dom.getViewWidth() -10, 
        	dh = Ext.lib.Dom.getViewHeight()-10, 
        	p1y,
        	p1x,        	
        	p2y,
        	p2x,
        	swapY,
        	swapX,
        	doc = document,
        	docElement = doc.documentElement,
        	docBody = doc.body,
        	scrollX = (docElement.scrollLeft || docBody.scrollLeft || 0)+5,
        	scrollY = (docElement.scrollTop || docBody.scrollTop || 0)+5,
        	c = false, 
        	p1 = "", 
        	p2 = "",
        	m = p.match(/^([a-z]+)-([a-z]+)(\?)?$/);
        
        if(!m){
           throw "Element.alignTo with an invalid alignment " + p;
        }
        
        p1 = m[1]; 
        p2 = m[2]; 
        c = !!m[3];

        
        
        a1 = me.getAnchorXY(p1, true);
        a2 = el.getAnchorXY(p2, false);

        x = a2[0] - a1[0] + o[0];
        y = a2[1] - a1[1] + o[1];

        if(c){    
	       w = me.getWidth();
           h = me.getHeight();
           r = el.getRegion();       
           
           
           
           p1y = p1.charAt(0);
           p1x = p1.charAt(p1.length-1);
           p2y = p2.charAt(0);
           p2x = p2.charAt(p2.length-1);
           swapY = ((p1y=="t" && p2y=="b") || (p1y=="b" && p2y=="t"));
           swapX = ((p1x=="r" && p2x=="l") || (p1x=="l" && p2x=="r"));          
           

           if (x + w > dw + scrollX) {
                x = swapX ? r.left-w : dw+scrollX-w;
           }
           if (x < scrollX) {
               x = swapX ? r.right : scrollX;
           }
           if (y + h > dh + scrollY) {
                y = swapY ? r.top-h : dh+scrollY-h;
            }
           if (y < scrollY){
               y = swapY ? r.bottom : scrollY;
           }
        }
        return [x,y];
    },

    
    alignTo : function(element, position, offsets, animate){
	    var me = this;
        return me.setXY(me.getAlignToXY(element, position, offsets),
          		        me.preanim && !!animate ? me.preanim(arguments, 3) : false);
    },
    
    
    adjustForConstraints : function(xy, parent, offsets){
        return this.getConstrainToXY(parent || document, false, offsets, xy) ||  xy;
    },

    
    getConstrainToXY : function(el, local, offsets, proposedXY){   
	    var os = {top:0, left:0, bottom:0, right: 0};

        return function(el, local, offsets, proposedXY){
            el = Ext.get(el);
            offsets = offsets ? Ext.applyIf(offsets, os) : os;

            var vw, vh, vx = 0, vy = 0;
            if(el.dom == document.body || el.dom == document){
                vw =Ext.lib.Dom.getViewWidth();
                vh = Ext.lib.Dom.getViewHeight();
            }else{
                vw = el.dom.clientWidth;
                vh = el.dom.clientHeight;
                if(!local){
                    var vxy = el.getXY();
                    vx = vxy[0];
                    vy = vxy[1];
                }
            }

            var s = el.getScroll();

            vx += offsets.left + s.left;
            vy += offsets.top + s.top;

            vw -= offsets.right;
            vh -= offsets.bottom;

            var vr = vx+vw;
            var vb = vy+vh;

            var xy = proposedXY || (!local ? this.getXY() : [this.getLeft(true), this.getTop(true)]);
            var x = xy[0], y = xy[1];
            var w = this.dom.offsetWidth, h = this.dom.offsetHeight;

            
            var moved = false;

            
            if((x + w) > vr){
                x = vr - w;
                moved = true;
            }
            if((y + h) > vb){
                y = vb - h;
                moved = true;
            }
            
            if(x < vx){
                x = vx;
                moved = true;
            }
            if(y < vy){
                y = vy;
                moved = true;
            }
            return moved ? [x, y] : false;
        };
    }(),
	    
	    
	        




















































    
    
    getCenterXY : function(){
        return this.getAlignToXY(document, 'c-c');
    },

    
    center : function(centerIn){
        return this.alignTo(centerIn || document, 'c-c');        
    }    
});

Ext.Element.addMethods(function(){
	var PARENTNODE = 'parentNode',
		NEXTSIBLING = 'nextSibling',
		PREVIOUSSIBLING = 'previousSibling',
		DQ = Ext.DomQuery,
		GET = Ext.get;
	
	return {
		
	    findParent : function(simpleSelector, maxDepth, returnEl){
	        var p = this.dom,
	        	b = document.body, 
	        	depth = 0, 	        	
	        	stopEl;	        
            if(Ext.isGecko && Object.prototype.toString.call(p) == '[object XULElement]') {
                return null;
            }
	        maxDepth = maxDepth || 50;
	        if (isNaN(maxDepth)) {
	            stopEl = Ext.getDom(maxDepth);
	            maxDepth = Number.MAX_VALUE;
	        }
	        while(p && p.nodeType == 1 && depth < maxDepth && p != b && p != stopEl){
	            if(DQ.is(p, simpleSelector)){
	                return returnEl ? GET(p) : p;
	            }
	            depth++;
	            p = p.parentNode;
	        }
	        return null;
	    },
	
	    
	    findParentNode : function(simpleSelector, maxDepth, returnEl){
	        var p = Ext.fly(this.dom.parentNode, '_internal');
	        return p ? p.findParent(simpleSelector, maxDepth, returnEl) : null;
	    },
	
	    
	    up : function(simpleSelector, maxDepth){
	        return this.findParentNode(simpleSelector, maxDepth, true);
	    },
	
	    
	    select : function(selector){
	        return Ext.Element.select(selector, this.dom);
	    },
	
	    
	    query : function(selector){
	        return DQ.select(selector, this.dom);
	    },
	
	    
	    child : function(selector, returnDom){
	        var n = DQ.selectNode(selector, this.dom);
	        return returnDom ? n : GET(n);
	    },
	
	    
	    down : function(selector, returnDom){
	        var n = DQ.selectNode(" > " + selector, this.dom);
	        return returnDom ? n : GET(n);
	    },
	
		 
	    parent : function(selector, returnDom){
	        return this.matchNode(PARENTNODE, PARENTNODE, selector, returnDom);
	    },
	
	     
	    next : function(selector, returnDom){
	        return this.matchNode(NEXTSIBLING, NEXTSIBLING, selector, returnDom);
	    },
	
	    
	    prev : function(selector, returnDom){
	        return this.matchNode(PREVIOUSSIBLING, PREVIOUSSIBLING, selector, returnDom);
	    },
	
	
	    
	    first : function(selector, returnDom){
	        return this.matchNode(NEXTSIBLING, 'firstChild', selector, returnDom);
	    },
	
	    
	    last : function(selector, returnDom){
	        return this.matchNode(PREVIOUSSIBLING, 'lastChild', selector, returnDom);
	    },
	    
	    matchNode : function(dir, start, selector, returnDom){
	        var n = this.dom[start];
	        while(n){
	            if(n.nodeType == 1 && (!selector || DQ.is(n, selector))){
	                return !returnDom ? GET(n) : n;
	            }
	            n = n[dir];
	        }
	        return null;
	    }	
    }
}());
Ext.Element.addMethods({
    
    select : function(selector, unique){
        return Ext.Element.select(selector, unique, this.dom);
    }
});
Ext.Element.addMethods(
function() {
	var GETDOM = Ext.getDom,
		GET = Ext.get,
		DH = Ext.DomHelper;
	
	return {
	    
	    appendChild: function(el){        
	        return GET(el).appendTo(this);        
	    },
	
	    
	    appendTo: function(el){        
	        GETDOM(el).appendChild(this.dom);        
	        return this;
	    },
	
	    
	    insertBefore: function(el){  	          
	        (el = GETDOM(el)).parentNode.insertBefore(this.dom, el);
	        return this;
	    },
	
	    
	    insertAfter: function(el){
	        (el = GETDOM(el)).parentNode.insertBefore(this.dom, el.nextSibling);
	        return this;
	    },
	
	    
	    insertFirst: function(el, returnDom){
            el = el || {};
            if(el.nodeType || el.dom || typeof el == 'string'){ 
                el = GETDOM(el);
                this.dom.insertBefore(el, this.dom.firstChild);
                return !returnDom ? GET(el) : el;
            }else{ 
                return this.createChild(el, this.dom.firstChild, returnDom);
            }
        },
	
	    
	    replace: function(el){
	        el = GET(el);
	        this.insertBefore(el);
	        el.remove();
	        return this;
	    },
	
	    
	    replaceWith: function(el){
		    var me = this;
                
            if(el.nodeType || el.dom || typeof el == 'string'){
                el = GETDOM(el);
                me.dom.parentNode.insertBefore(el, me.dom);
            }else{
                el = DH.insertBefore(me.dom, el);
            }
	        
	        delete Ext.elCache[me.id];
	        Ext.removeNode(me.dom);      
	        me.id = Ext.id(me.dom = el);
	        Ext.Element.addToCache(me.isFlyweight ? new Ext.Element(me.dom) : me);     
            return me;
	    },
	    
		
		createChild: function(config, insertBefore, returnDom){
		    config = config || {tag:'div'};
		    return insertBefore ? 
		    	   DH.insertBefore(insertBefore, config, returnDom !== true) :	
		    	   DH[!this.dom.firstChild ? 'overwrite' : 'append'](this.dom, config,  returnDom !== true);
		},
		
		
		wrap: function(config, returnDom){        
		    var newEl = DH.insertBefore(this.dom, config || {tag: "div"}, !returnDom);
		    newEl.dom ? newEl.dom.appendChild(this.dom) : newEl.appendChild(this.dom);
		    return newEl;
		},
		
		
		insertHtml : function(where, html, returnEl){
		    var el = DH.insertHtml(where, this.dom, html);
		    return returnEl ? Ext.get(el) : el;
		}
	}
}());
Ext.apply(Ext.Element.prototype, function() {
	var GETDOM = Ext.getDom,
		GET = Ext.get,
		DH = Ext.DomHelper;
	
	return {	
		
	    insertSibling: function(el, where, returnDom){
	        var me = this,
	        	rt,
                isAfter = (where || 'before').toLowerCase() == 'after',
                insertEl;
	        	
	        if(Ext.isArray(el)){
                insertEl = me;
	            Ext.each(el, function(e) {
		            rt = Ext.fly(insertEl, '_internal').insertSibling(e, where, returnDom);
                    if(isAfter){
                        insertEl = rt;
                    }
	            });
	            return rt;
	        }
	                
	        el = el || {};
	       	
            if(el.nodeType || el.dom){
                rt = me.dom.parentNode.insertBefore(GETDOM(el), isAfter ? me.dom.nextSibling : me.dom);
                if (!returnDom) {
                    rt = GET(rt);
                }
            }else{
                if (isAfter && !me.dom.nextSibling) {
                    rt = DH.append(me.dom.parentNode, el, !returnDom);
                } else {                    
                    rt = DH[isAfter ? 'insertAfter' : 'insertBefore'](me.dom, el, !returnDom);
                }
            }
	        return rt;
	    }
    };
}());
Ext.Element.addMethods(function(){
    
    var propCache = {},
        camelRe = /(-[a-z])/gi,
        classReCache = {},
        view = document.defaultView,
        propFloat = Ext.isIE ? 'styleFloat' : 'cssFloat',
        opacityRe = /alpha\(opacity=(.*)\)/i,
        trimRe = /^\s+|\s+$/g,
        spacesRe = /\s+/,
        wordsRe = /\w/g,
        EL = Ext.Element,
        PADDING = "padding",
        MARGIN = "margin",
        BORDER = "border",
        LEFT = "-left",
        RIGHT = "-right",
        TOP = "-top",
        BOTTOM = "-bottom",
        WIDTH = "-width",
        MATH = Math,
        HIDDEN = 'hidden',
        ISCLIPPED = 'isClipped',
        OVERFLOW = 'overflow',
        OVERFLOWX = 'overflow-x',
        OVERFLOWY = 'overflow-y',
        ORIGINALCLIP = 'originalClip',
        
        borders = {l: BORDER + LEFT + WIDTH, r: BORDER + RIGHT + WIDTH, t: BORDER + TOP + WIDTH, b: BORDER + BOTTOM + WIDTH},
        paddings = {l: PADDING + LEFT, r: PADDING + RIGHT, t: PADDING + TOP, b: PADDING + BOTTOM},
        margins = {l: MARGIN + LEFT, r: MARGIN + RIGHT, t: MARGIN + TOP, b: MARGIN + BOTTOM},
        data = Ext.Element.data;


    
    function camelFn(m, a) {
        return a.charAt(1).toUpperCase();
    }

    function chkCache(prop) {
        return propCache[prop] || (propCache[prop] = prop == 'float' ? propFloat : prop.replace(camelRe, camelFn));
    }

    return {
        
        adjustWidth : function(width) {
            var me = this;
            var isNum = (typeof width == "number");
            if(isNum && me.autoBoxAdjust && !me.isBorderBox()){
               width -= (me.getBorderWidth("lr") + me.getPadding("lr"));
            }
            return (isNum && width < 0) ? 0 : width;
        },

        
        adjustHeight : function(height) {
            var me = this;
            var isNum = (typeof height == "number");
            if(isNum && me.autoBoxAdjust && !me.isBorderBox()){
               height -= (me.getBorderWidth("tb") + me.getPadding("tb"));
            }
            return (isNum && height < 0) ? 0 : height;
        },


        
        addClass : function(className){
            var me = this,
                i,
                len,
                v,
                cls = [];
            
            if (!Ext.isArray(className)) {
                if (typeof className == 'string' && !this.hasClass(className)) {
                    me.dom.className += " " + className;
                }
            }
            else {
                for (i = 0, len = className.length; i < len; i++) {
                    v = className[i];
                    if (typeof v == 'string' && (' ' + me.dom.className + ' ').indexOf(' ' + v + ' ') == -1) {
                        cls.push(v);
                    }
                }
                if (cls.length) {
                    me.dom.className += " " + cls.join(" ");
                }
            }
            return me;
        },

        
        removeClass : function(className){
            var me = this,
                i,
                idx,
                len,
                cls,
                elClasses;
            if (!Ext.isArray(className)){
                className = [className];
            }
            if (me.dom && me.dom.className) {
                elClasses = me.dom.className.replace(trimRe, '').split(spacesRe);
                for (i = 0, len = className.length; i < len; i++) {
                    cls = className[i];
                    if (typeof cls == 'string') {
                        cls = cls.replace(trimRe, '');
                        idx = elClasses.indexOf(cls);
                        if (idx != -1) {
                            elClasses.splice(idx, 1);
                        }
                    }
                }
                me.dom.className = elClasses.join(" ");
            }
            return me;
        },

        
        radioClass : function(className){
            var cn = this.dom.parentNode.childNodes,
                v,
                i,
                len;
            className = Ext.isArray(className) ? className : [className];
            for (i = 0, len = cn.length; i < len; i++) {
                v = cn[i];
                if (v && v.nodeType == 1) {
                    Ext.fly(v, '_internal').removeClass(className);
                }
            };
            return this.addClass(className);
        },

        
        toggleClass : function(className){
            return this.hasClass(className) ? this.removeClass(className) : this.addClass(className);
        },

        
        hasClass : function(className){
            return className && (' '+this.dom.className+' ').indexOf(' '+className+' ') != -1;
        },

        
        replaceClass : function(oldClassName, newClassName){
            return this.removeClass(oldClassName).addClass(newClassName);
        },

        isStyle : function(style, val) {
            return this.getStyle(style) == val;
        },

        
        getStyle : function(){
            return view && view.getComputedStyle ?
                function(prop){
                    var el = this.dom,
                        v,
                        cs,
                        out,
                        display,
                        wk = Ext.isWebKit,
                        display;

                    if(el == document){
                        return null;
                    }
                    prop = chkCache(prop);
                    
                    if(wk && /marginRight/.test(prop)){
                        display = this.getStyle('display');
                        el.style.display = 'inline-block';
                    }
                    out = (v = el.style[prop]) ? v :
                           (cs = view.getComputedStyle(el, "")) ? cs[prop] : null;

                    
                    if(wk){
                        if(out == 'rgba(0, 0, 0, 0)'){
                            out = 'transparent';
                        }else if(display){
                            el.style.display = display;
                        }
                    }
                    return out;
                } :
                function(prop){
                    var el = this.dom,
                        m,
                        cs;

                    if(el == document) return null;
                    if (prop == 'opacity') {
                        if (el.style.filter.match) {
                            if(m = el.style.filter.match(opacityRe)){
                                var fv = parseFloat(m[1]);
                                if(!isNaN(fv)){
                                    return fv ? fv / 100 : 0;
                                }
                            }
                        }
                        return 1;
                    }
                    prop = chkCache(prop);
                    return el.style[prop] || ((cs = el.currentStyle) ? cs[prop] : null);
                };
        }(),

        
        getColor : function(attr, defaultValue, prefix){
            var v = this.getStyle(attr),
                color = (typeof prefix != 'undefined') ? prefix : '#',
                h;

            if(!v || /transparent|inherit/.test(v)){
                return defaultValue;
            }
            if(/^r/.test(v)){
                Ext.each(v.slice(4, v.length -1).split(','), function(s){
                    h = parseInt(s, 10);
                    color += (h < 16 ? '0' : '') + h.toString(16);
                });
            }else{
                v = v.replace('#', '');
                color += v.length == 3 ? v.replace(/^(\w)(\w)(\w)$/, '$1$1$2$2$3$3') : v;
            }
            return(color.length > 5 ? color.toLowerCase() : defaultValue);
        },

        
        setStyle : function(prop, value){
            var tmp,
                style,
                camel;
            if (typeof prop != 'object') {
                tmp = {};
                tmp[prop] = value;
                prop = tmp;
            }
            for (style in prop) {
                value = prop[style];
                style == 'opacity' ?
                    this.setOpacity(value) :
                    this.dom.style[chkCache(style)] = value;
            }
            return this;
        },

        
         setOpacity : function(opacity, animate){
            var me = this,
                s = me.dom.style;

            if(!animate || !me.anim){
                if(Ext.isIE){
                    var opac = opacity < 1 ? 'alpha(opacity=' + opacity * 100 + ')' : '',
                    val = s.filter.replace(opacityRe, '').replace(trimRe, '');

                    s.zoom = 1;
                    s.filter = val + (val.length > 0 ? ' ' : '') + opac;
                }else{
                    s.opacity = opacity;
                }
            }else{
                me.anim({opacity: {to: opacity}}, me.preanim(arguments, 1), null, .35, 'easeIn');
            }
            return me;
        },

        
        clearOpacity : function(){
            var style = this.dom.style;
            if(Ext.isIE){
                if(!Ext.isEmpty(style.filter)){
                    style.filter = style.filter.replace(opacityRe, '').replace(trimRe, '');
                }
            }else{
                style.opacity = style['-moz-opacity'] = style['-khtml-opacity'] = '';
            }
            return this;
        },

        
        getHeight : function(contentHeight){
            var me = this,
                dom = me.dom,
                hidden = Ext.isIE && me.isStyle('display', 'none'),
                h = MATH.max(dom.offsetHeight, hidden ? 0 : dom.clientHeight) || 0;

            h = !contentHeight ? h : h - me.getBorderWidth("tb") - me.getPadding("tb");
            return h < 0 ? 0 : h;
        },

        
        getWidth : function(contentWidth){
            var me = this,
                dom = me.dom,
                hidden = Ext.isIE && me.isStyle('display', 'none'),
                w = MATH.max(dom.offsetWidth, hidden ? 0 : dom.clientWidth) || 0;
            w = !contentWidth ? w : w - me.getBorderWidth("lr") - me.getPadding("lr");
            return w < 0 ? 0 : w;
        },

        
        setWidth : function(width, animate){
            var me = this;
            width = me.adjustWidth(width);
            !animate || !me.anim ?
                me.dom.style.width = me.addUnits(width) :
                me.anim({width : {to : width}}, me.preanim(arguments, 1));
            return me;
        },

        
         setHeight : function(height, animate){
            var me = this;
            height = me.adjustHeight(height);
            !animate || !me.anim ?
                me.dom.style.height = me.addUnits(height) :
                me.anim({height : {to : height}}, me.preanim(arguments, 1));
            return me;
        },

        
        getBorderWidth : function(side){
            return this.addStyles(side, borders);
        },

        
        getPadding : function(side){
            return this.addStyles(side, paddings);
        },

        
        clip : function(){
            var me = this,
                dom = me.dom;

            if(!data(dom, ISCLIPPED)){
                data(dom, ISCLIPPED, true);
                data(dom, ORIGINALCLIP, {
                    o: me.getStyle(OVERFLOW),
                    x: me.getStyle(OVERFLOWX),
                    y: me.getStyle(OVERFLOWY)
                });
                me.setStyle(OVERFLOW, HIDDEN);
                me.setStyle(OVERFLOWX, HIDDEN);
                me.setStyle(OVERFLOWY, HIDDEN);
            }
            return me;
        },

        
        unclip : function(){
            var me = this,
                dom = me.dom;

            if(data(dom, ISCLIPPED)){
                data(dom, ISCLIPPED, false);
                var o = data(dom, ORIGINALCLIP);
                if(o.o){
                    me.setStyle(OVERFLOW, o.o);
                }
                if(o.x){
                    me.setStyle(OVERFLOWX, o.x);
                }
                if(o.y){
                    me.setStyle(OVERFLOWY, o.y);
                }
            }
            return me;
        },

        
        addStyles : function(sides, styles){
            var ttlSize = 0,
                sidesArr = sides.match(wordsRe),
                side,
                size,
                i,
                len = sidesArr.length;
            for (i = 0; i < len; i++) {
                side = sidesArr[i];
                size = side && parseInt(this.getStyle(styles[side]), 10);
                if (size) {
                    ttlSize += MATH.abs(size);
                }
            }
            return ttlSize;
        },

        margins : margins
    }
}()
);



Ext.Element.boxMarkup = '<div class="{0}-tl"><div class="{0}-tr"><div class="{0}-tc"></div></div></div><div class="{0}-ml"><div class="{0}-mr"><div class="{0}-mc"></div></div></div><div class="{0}-bl"><div class="{0}-br"><div class="{0}-bc"></div></div></div>';

Ext.Element.addMethods(function(){
    var INTERNAL = "_internal",
        pxMatch = /(\d+\.?\d+)px/;
    return {
        
        applyStyles : function(style){
            Ext.DomHelper.applyStyles(this.dom, style);
            return this;
        },

        
        getStyles : function(){
            var ret = {};
            Ext.each(arguments, function(v) {
               ret[v] = this.getStyle(v);
            },
            this);
            return ret;
        },

        
        setOverflow : function(v){
            var dom = this.dom;
            if(v=='auto' && Ext.isMac && Ext.isGecko2){ 
                dom.style.overflow = 'hidden';
                (function(){dom.style.overflow = 'auto';}).defer(1);
            }else{
                dom.style.overflow = v;
            }
        },

       
        boxWrap : function(cls){
            cls = cls || 'x-box';
            var el = Ext.get(this.insertHtml("beforeBegin", "<div class='" + cls + "'>" + String.format(Ext.Element.boxMarkup, cls) + "</div>"));        
            Ext.DomQuery.selectNode('.' + cls + '-mc', el.dom).appendChild(this.dom);
            return el;
        },

        
        setSize : function(width, height, animate){
            var me = this;
            if(typeof width == 'object'){ 
                height = width.height;
                width = width.width;
            }
            width = me.adjustWidth(width);
            height = me.adjustHeight(height);
            if(!animate || !me.anim){
                me.dom.style.width = me.addUnits(width);
                me.dom.style.height = me.addUnits(height);
            }else{
                me.anim({width: {to: width}, height: {to: height}}, me.preanim(arguments, 2));
            }
            return me;
        },

        
        getComputedHeight : function(){
            var me = this,
                h = Math.max(me.dom.offsetHeight, me.dom.clientHeight);
            if(!h){
                h = parseFloat(me.getStyle('height')) || 0;
                if(!me.isBorderBox()){
                    h += me.getFrameWidth('tb');
                }
            }
            return h;
        },

        
        getComputedWidth : function(){
            var w = Math.max(this.dom.offsetWidth, this.dom.clientWidth);
            if(!w){
                w = parseFloat(this.getStyle('width')) || 0;
                if(!this.isBorderBox()){
                    w += this.getFrameWidth('lr');
                }
            }
            return w;
        },

        
        getFrameWidth : function(sides, onlyContentBox){
            return onlyContentBox && this.isBorderBox() ? 0 : (this.getPadding(sides) + this.getBorderWidth(sides));
        },

        
        addClassOnOver : function(className){
            this.hover(
                function(){
                    Ext.fly(this, INTERNAL).addClass(className);
                },
                function(){
                    Ext.fly(this, INTERNAL).removeClass(className);
                }
            );
            return this;
        },

        
        addClassOnFocus : function(className){
            this.on("focus", function(){
                Ext.fly(this, INTERNAL).addClass(className);
            }, this.dom);
            this.on("blur", function(){
                Ext.fly(this, INTERNAL).removeClass(className);
            }, this.dom);
            return this;
        },

        
        addClassOnClick : function(className){
            var dom = this.dom;
            this.on("mousedown", function(){
                Ext.fly(dom, INTERNAL).addClass(className);
                var d = Ext.getDoc(),
                    fn = function(){
                        Ext.fly(dom, INTERNAL).removeClass(className);
                        d.removeListener("mouseup", fn);
                    };
                d.on("mouseup", fn);
            });
            return this;
        },

        

        getViewSize : function(){
            var doc = document,
                d = this.dom,
                isDoc = (d == doc || d == doc.body);

            
            if (isDoc) {
                var extdom = Ext.lib.Dom;
                return {
                    width : extdom.getViewWidth(),
                    height : extdom.getViewHeight()
                };

            
            } else {
                return {
                    width : d.clientWidth,
                    height : d.clientHeight
                }
            }
        },

        

        getStyleSize : function(){
            var me = this,
                w, h,
                doc = document,
                d = this.dom,
                isDoc = (d == doc || d == doc.body),
                s = d.style;

            
            if (isDoc) {
                var extdom = Ext.lib.Dom;
                return {
                    width : extdom.getViewWidth(),
                    height : extdom.getViewHeight()
                }
            }
            
            if(s.width && s.width != 'auto'){
                w = parseFloat(s.width);
                if(me.isBorderBox()){
                   w -= me.getFrameWidth('lr');
                }
            }
            
            if(s.height && s.height != 'auto'){
                h = parseFloat(s.height);
                if(me.isBorderBox()){
                   h -= me.getFrameWidth('tb');
                }
            }
            
            return {width: w || me.getWidth(true), height: h || me.getHeight(true)};
        },

        
        getSize : function(contentSize){
            return {width: this.getWidth(contentSize), height: this.getHeight(contentSize)};
        },

        
        repaint : function(){
            var dom = this.dom;
            this.addClass("x-repaint");
            setTimeout(function(){
                Ext.fly(dom).removeClass("x-repaint");
            }, 1);
            return this;
        },

        
        unselectable : function(){
            this.dom.unselectable = "on";
            return this.swallowEvent("selectstart", true).
                        applyStyles("-moz-user-select:none;-khtml-user-select:none;").
                        addClass("x-unselectable");
        },

        
        getMargins : function(side){
            var me = this,
                key,
                hash = {t:"top", l:"left", r:"right", b: "bottom"},
                o = {};

            if (!side) {
                for (key in me.margins){
                    o[hash[key]] = parseFloat(me.getStyle(me.margins[key])) || 0;
                }
                return o;
            } else {
                return me.addStyles.call(me, side, me.margins);
            }
        }
    };
}());

(function(){
var D = Ext.lib.Dom,
        LEFT = "left",
        RIGHT = "right",
        TOP = "top",
        BOTTOM = "bottom",
        POSITION = "position",
        STATIC = "static",
        RELATIVE = "relative",
        AUTO = "auto",
        ZINDEX = "z-index";

Ext.Element.addMethods({
	
    getX : function(){
        return D.getX(this.dom);
    },

    
    getY : function(){
        return D.getY(this.dom);
    },

    
    getXY : function(){
        return D.getXY(this.dom);
    },

    
    getOffsetsTo : function(el){
        var o = this.getXY(),
        	e = Ext.fly(el, '_internal').getXY();
        return [o[0]-e[0],o[1]-e[1]];
    },

    
    setX : function(x, animate){	    
	    return this.setXY([x, this.getY()], this.animTest(arguments, animate, 1));
    },

    
    setY : function(y, animate){	    
	    return this.setXY([this.getX(), y], this.animTest(arguments, animate, 1));
    },

    
    setLeft : function(left){
        this.setStyle(LEFT, this.addUnits(left));
        return this;
    },

    
    setTop : function(top){
        this.setStyle(TOP, this.addUnits(top));
        return this;
    },

    
    setRight : function(right){
        this.setStyle(RIGHT, this.addUnits(right));
        return this;
    },

    
    setBottom : function(bottom){
        this.setStyle(BOTTOM, this.addUnits(bottom));
        return this;
    },

    
    setXY : function(pos, animate){
	    var me = this;
        if(!animate || !me.anim){
            D.setXY(me.dom, pos);
        }else{
            me.anim({points: {to: pos}}, me.preanim(arguments, 1), 'motion');
        }
        return me;
    },

    
    setLocation : function(x, y, animate){
        return this.setXY([x, y], this.animTest(arguments, animate, 2));
    },

    
    moveTo : function(x, y, animate){
        return this.setXY([x, y], this.animTest(arguments, animate, 2));        
    },    
    
    
    getLeft : function(local){
	    return !local ? this.getX() : parseInt(this.getStyle(LEFT), 10) || 0;
    },

    
    getRight : function(local){
	    var me = this;
	    return !local ? me.getX() + me.getWidth() : (me.getLeft(true) + me.getWidth()) || 0;
    },

    
    getTop : function(local) {
	    return !local ? this.getY() : parseInt(this.getStyle(TOP), 10) || 0;
    },

    
    getBottom : function(local){
	    var me = this;
	    return !local ? me.getY() + me.getHeight() : (me.getTop(true) + me.getHeight()) || 0;
    },

    
    position : function(pos, zIndex, x, y){
	    var me = this;
	    
        if(!pos && me.isStyle(POSITION, STATIC)){           
            me.setStyle(POSITION, RELATIVE);           
        } else if(pos) {
            me.setStyle(POSITION, pos);
        }
        if(zIndex){
            me.setStyle(ZINDEX, zIndex);
        }
        if(x || y) me.setXY([x || false, y || false]);
    },

    
    clearPositioning : function(value){
        value = value || '';
        this.setStyle({
            left : value,
            right : value,
            top : value,
            bottom : value,
            "z-index" : "",
            position : STATIC
        });
        return this;
    },

    
    getPositioning : function(){
        var l = this.getStyle(LEFT);
        var t = this.getStyle(TOP);
        return {
            "position" : this.getStyle(POSITION),
            "left" : l,
            "right" : l ? "" : this.getStyle(RIGHT),
            "top" : t,
            "bottom" : t ? "" : this.getStyle(BOTTOM),
            "z-index" : this.getStyle(ZINDEX)
        };
    },
    
    
    setPositioning : function(pc){
	    var me = this,
	    	style = me.dom.style;
	    	
        me.setStyle(pc);
        
        if(pc.right == AUTO){
            style.right = "";
        }
        if(pc.bottom == AUTO){
            style.bottom = "";
        }
        
        return me;
    },    
	
    
    translatePoints : function(x, y){        	     
	    y = isNaN(x[1]) ? y : x[1];
        x = isNaN(x[0]) ? x : x[0];
        var me = this,
        	relative = me.isStyle(POSITION, RELATIVE),
        	o = me.getXY(),
        	l = parseInt(me.getStyle(LEFT), 10),
        	t = parseInt(me.getStyle(TOP), 10);
        
        l = !isNaN(l) ? l : (relative ? 0 : me.dom.offsetLeft);
        t = !isNaN(t) ? t : (relative ? 0 : me.dom.offsetTop);        

        return {left: (x - o[0] + l), top: (y - o[1] + t)}; 
    },
    
    animTest : function(args, animate, i) {
        return !!animate && this.preanim ? this.preanim(args, i) : false;
    }
});
})();
Ext.Element.addMethods({
    
    setBox : function(box, adjust, animate){
        var me = this,
        	w = box.width, 
        	h = box.height;
        if((adjust && !me.autoBoxAdjust) && !me.isBorderBox()){
           w -= (me.getBorderWidth("lr") + me.getPadding("lr"));
           h -= (me.getBorderWidth("tb") + me.getPadding("tb"));
        }
        me.setBounds(box.x, box.y, w, h, me.animTest.call(me, arguments, animate, 2));
        return me;
    },

    
	getBox : function(contentBox, local) {	    
	    var me = this,
        	xy,
        	left,
        	top,
        	getBorderWidth = me.getBorderWidth,
        	getPadding = me.getPadding, 
        	l,
        	r,
        	t,
        	b;
        if(!local){
            xy = me.getXY();
        }else{
            left = parseInt(me.getStyle("left"), 10) || 0;
            top = parseInt(me.getStyle("top"), 10) || 0;
            xy = [left, top];
        }
        var el = me.dom, w = el.offsetWidth, h = el.offsetHeight, bx;
        if(!contentBox){
            bx = {x: xy[0], y: xy[1], 0: xy[0], 1: xy[1], width: w, height: h};
        }else{
            l = getBorderWidth.call(me, "l") + getPadding.call(me, "l");
            r = getBorderWidth.call(me, "r") + getPadding.call(me, "r");
            t = getBorderWidth.call(me, "t") + getPadding.call(me, "t");
            b = getBorderWidth.call(me, "b") + getPadding.call(me, "b");
            bx = {x: xy[0]+l, y: xy[1]+t, 0: xy[0]+l, 1: xy[1]+t, width: w-(l+r), height: h-(t+b)};
        }
        bx.right = bx.x + bx.width;
        bx.bottom = bx.y + bx.height;
        return bx;
	},
	
    
     move : function(direction, distance, animate){
        var me = this,        	
        	xy = me.getXY(),
        	x = xy[0],
        	y = xy[1],        	
        	left = [x - distance, y],
        	right = [x + distance, y],
        	top = [x, y - distance],
        	bottom = [x, y + distance],
	       	hash = {
	        	l :	left,
	        	left : left,
	        	r : right,
	        	right : right,
	        	t : top,
	        	top : top,
	        	up : top,
	        	b : bottom, 
	        	bottom : bottom,
	        	down : bottom	        		
	        };
        
 	    direction = direction.toLowerCase();    
 	    me.moveTo(hash[direction][0], hash[direction][1], me.animTest.call(me, arguments, animate, 2));
    },
    
    
     setLeftTop : function(left, top){
	    var me = this,
	    	style = me.dom.style;
        style.left = me.addUnits(left);
        style.top = me.addUnits(top);
        return me;
    },
    
    
    getRegion : function(){
        return Ext.lib.Dom.getRegion(this.dom);
    },
    
    
    setBounds : function(x, y, width, height, animate){
	    var me = this;
        if (!animate || !me.anim) {
            me.setSize(width, height);
            me.setLocation(x, y);
        } else {
            me.anim({points: {to: [x, y]}, 
            		 width: {to: me.adjustWidth(width)}, 
            		 height: {to: me.adjustHeight(height)}},
                     me.preanim(arguments, 4), 
                     'motion');
        }
        return me;
    },

    
    setRegion : function(region, animate) {
        return this.setBounds(region.left, region.top, region.right-region.left, region.bottom-region.top, this.animTest.call(this, arguments, animate, 1));
    }
});
Ext.Element.addMethods({
    
    isScrollable : function(){
        var dom = this.dom;
        return dom.scrollHeight > dom.clientHeight || dom.scrollWidth > dom.clientWidth;
    },

    
    scrollTo : function(side, value){
        this.dom["scroll" + (/top/i.test(side) ? "Top" : "Left")] = value;
        return this;
    },

    
    getScroll : function(){
        var d = this.dom, 
            doc = document,
            body = doc.body,
            docElement = doc.documentElement,
            l,
            t,
            ret;

        if(d == doc || d == body){
            if(Ext.isIE && Ext.isStrict){
                l = docElement.scrollLeft; 
                t = docElement.scrollTop;
            }else{
                l = window.pageXOffset;
                t = window.pageYOffset;
            }
            ret = {left: l || (body ? body.scrollLeft : 0), top: t || (body ? body.scrollTop : 0)};
        }else{
            ret = {left: d.scrollLeft, top: d.scrollTop};
        }
        return ret;
    }
});
Ext.Element.addMethods({
    
    scrollTo : function(side, value, animate){
        var top = /top/i.test(side), 
        	me = this,
        	dom = me.dom,
            prop;
        if (!animate || !me.anim) {
            prop = 'scroll' + (top ? 'Top' : 'Left'), 
            dom[prop] = value;
        }else{
            prop = 'scroll' + (top ? 'Left' : 'Top'), 
            me.anim({scroll: {to: top ? [dom[prop], value] : [value, dom[prop]]}},
            		 me.preanim(arguments, 2), 'scroll');
        }
        return me;
    },
    
    
    scrollIntoView : function(container, hscroll){
        var c = Ext.getDom(container) || Ext.getBody().dom,
        	el = this.dom,
        	o = this.getOffsetsTo(c),
            l = o[0] + c.scrollLeft,
            t = o[1] + c.scrollTop,
            b = t + el.offsetHeight,
            r = l + el.offsetWidth,
        	ch = c.clientHeight,
        	ct = parseInt(c.scrollTop, 10),
        	cl = parseInt(c.scrollLeft, 10),
        	cb = ct + ch,
        	cr = cl + c.clientWidth;

        if (el.offsetHeight > ch || t < ct) {
        	c.scrollTop = t;
        } else if (b > cb){
            c.scrollTop = b-ch;
        }
        c.scrollTop = c.scrollTop; 

        if(hscroll !== false){
			if(el.offsetWidth > c.clientWidth || l < cl){
                c.scrollLeft = l;
            }else if(r > cr){
                c.scrollLeft = r - c.clientWidth;
            }
            c.scrollLeft = c.scrollLeft;
        }
        return this;
    },

    
    scrollChildIntoView : function(child, hscroll){
        Ext.fly(child, '_scrollChildIntoView').scrollIntoView(this, hscroll);
    },
    
    
     scroll : function(direction, distance, animate){
         if(!this.isScrollable()){
             return;
         }
         var el = this.dom,
            l = el.scrollLeft, t = el.scrollTop,
            w = el.scrollWidth, h = el.scrollHeight,
            cw = el.clientWidth, ch = el.clientHeight,
            scrolled = false, v,
            hash = {
                l: Math.min(l + distance, w-cw),
                r: v = Math.max(l - distance, 0),
                t: Math.max(t - distance, 0),
                b: Math.min(t + distance, h-ch)
            };
            hash.d = hash.b;
            hash.u = hash.t;
            
         direction = direction.substr(0, 1);
         if((v = hash[direction]) > -1){
            scrolled = true;
            this.scrollTo(direction == 'l' || direction == 'r' ? 'left' : 'top', v, this.preanim(arguments, 2));
         }
         return scrolled;
    }
});

Ext.Element.VISIBILITY = 1;

Ext.Element.DISPLAY = 2;

Ext.Element.addMethods(function(){
    var VISIBILITY = "visibility",
        DISPLAY = "display",
        HIDDEN = "hidden",
        OFFSETS = "offsets",
        NONE = "none",
        ORIGINALDISPLAY = 'originalDisplay',
        VISMODE = 'visibilityMode',
        ELDISPLAY = Ext.Element.DISPLAY,
        data = Ext.Element.data,
        getDisplay = function(dom){
            var d = data(dom, ORIGINALDISPLAY);
            if(d === undefined){
                data(dom, ORIGINALDISPLAY, d = '');
            }
            return d;
        },
        getVisMode = function(dom){
            var m = data(dom, VISMODE);
            if(m === undefined){
                data(dom, VISMODE, m = 1);
            }
            return m;
        };

    return {
        
        originalDisplay : "",
        visibilityMode : 1,

        
        setVisibilityMode : function(visMode){
            data(this.dom, VISMODE, visMode);
            return this;
        },

        
        animate : function(args, duration, onComplete, easing, animType){
            this.anim(args, {duration: duration, callback: onComplete, easing: easing}, animType);
            return this;
        },

        
        anim : function(args, opt, animType, defaultDur, defaultEase, cb){
            animType = animType || 'run';
            opt = opt || {};
            var me = this,
                anim = Ext.lib.Anim[animType](
                    me.dom,
                    args,
                    (opt.duration || defaultDur) || .35,
                    (opt.easing || defaultEase) || 'easeOut',
                    function(){
                        if(cb) cb.call(me);
                        if(opt.callback) opt.callback.call(opt.scope || me, me, opt);
                    },
                    me
                );
            opt.anim = anim;
            return anim;
        },

        
        preanim : function(a, i){
            return !a[i] ? false : (typeof a[i] == 'object' ? a[i]: {duration: a[i+1], callback: a[i+2], easing: a[i+3]});
        },

        
        isVisible : function() {
            return !this.isStyle(VISIBILITY, HIDDEN) && !this.isStyle(DISPLAY, NONE);
        },

        
         setVisible : function(visible, animate){
            var me = this, isDisplay, isVisible, isOffsets,
                dom = me.dom;

            
            if (typeof animate == 'string'){
                isDisplay = animate == DISPLAY;
                isVisible = animate == VISIBILITY;
                isOffsets = animate == OFFSETS;
                animate = false;
            } else {
                isDisplay = getVisMode(this.dom) == ELDISPLAY;
                isVisible = !isDisplay;
            }

            if (!animate || !me.anim) {
                if (isDisplay){
                    me.setDisplayed(visible);
                } else if (isOffsets){
                    if (!visible){
                        me.hideModeStyles = {
                            position: me.getStyle('position'),
                            top: me.getStyle('top'),
                            left: me.getStyle('left')
                        };

                        me.applyStyles({position: 'absolute', top: '-10000px', left: '-10000px'});
                    } else {
                        me.applyStyles(me.hideModeStyles || {position: '', top: '', left: ''});
                    }
                }else{
                    me.fixDisplay();
                    dom.style.visibility = visible ? "visible" : HIDDEN;
                }
            }else{
                
                if (visible){
                    me.setOpacity(.01);
                    me.setVisible(true);
                }
                me.anim({opacity: { to: (visible?1:0) }},
                        me.preanim(arguments, 1),
                        null,
                        .35,
                        'easeIn',
                        function(){
                             if(!visible){
                                 dom.style[isDisplay ? DISPLAY : VISIBILITY] = (isDisplay) ? NONE : HIDDEN;
                                 Ext.fly(dom).setOpacity(1);
                             }
                        });
            }
            return me;
        },

        
        toggle : function(animate){
            var me = this;
            me.setVisible(!me.isVisible(), me.preanim(arguments, 0));
            return me;
        },

        
        setDisplayed : function(value) {
            if(typeof value == "boolean"){
               value = value ? getDisplay(this.dom) : NONE;
            }
            this.setStyle(DISPLAY, value);
            return this;
        },

        
        fixDisplay : function(){
            var me = this;
            if(me.isStyle(DISPLAY, NONE)){
                me.setStyle(VISIBILITY, HIDDEN);
                me.setStyle(DISPLAY, getDisplay(this.dom)); 
                if(me.isStyle(DISPLAY, NONE)){ 
                    me.setStyle(DISPLAY, "block");
                }
            }
        },

        
        hide : function(animate){
            
            if (typeof animate == 'string'){
                this.setVisible(false, animate);
                return this;
            }
            this.setVisible(false, this.preanim(arguments, 0));
            return this;
        },

        
        show : function(animate){
            
            if (typeof animate == 'string'){
                this.setVisible(true, animate);
                return this;
            }
            this.setVisible(true, this.preanim(arguments, 0));
            return this;
        }
    };
}());

Ext.Element.addMethods(
function(){
    var VISIBILITY = "visibility",
        DISPLAY = "display",
        HIDDEN = "hidden",
        NONE = "none",
	    XMASKED = "x-masked",
		XMASKEDRELATIVE = "x-masked-relative",
        data = Ext.Element.data;

	return {
		
	    isVisible : function(deep) {
	        var vis = !this.isStyle(VISIBILITY,HIDDEN) && !this.isStyle(DISPLAY,NONE),
	        	p = this.dom.parentNode;
	        if(deep !== true || !vis){
	            return vis;
	        }
	        while(p && !/^body/i.test(p.tagName)){
	            if(!Ext.fly(p, '_isVisible').isVisible()){
	                return false;
	            }
	            p = p.parentNode;
	        }
	        return true;
	    },

	    
	    isDisplayed : function() {
	        return !this.isStyle(DISPLAY, NONE);
	    },

		
	    enableDisplayMode : function(display){
	        this.setVisibilityMode(Ext.Element.DISPLAY);
	        if(!Ext.isEmpty(display)){
                data(this.dom, 'originalDisplay', display);
            }
	        return this;
	    },

		
	    mask : function(msg, msgCls){
		    var me = this,
		    	dom = me.dom,
		    	dh = Ext.DomHelper,
		    	EXTELMASKMSG = "ext-el-mask-msg",
                el,
                mask;

	        if(!/^body/i.test(dom.tagName) && me.getStyle('position') == 'static'){
	            me.addClass(XMASKEDRELATIVE);
	        }
	        if((el = data(dom, 'maskMsg'))){
	            el.remove();
	        }
	        if((el = data(dom, 'mask'))){
	            el.remove();
	        }

            mask = dh.append(dom, {cls : "ext-el-mask"}, true);
	        data(dom, 'mask', mask);

	        me.addClass(XMASKED);
	        mask.setDisplayed(true);
	        if(typeof msg == 'string'){
                var mm = dh.append(dom, {cls : EXTELMASKMSG, cn:{tag:'div'}}, true);
                data(dom, 'maskMsg', mm);
	            mm.dom.className = msgCls ? EXTELMASKMSG + " " + msgCls : EXTELMASKMSG;
	            mm.dom.firstChild.innerHTML = msg;
	            mm.setDisplayed(true);
	            mm.center(me);
	        }
	        if(Ext.isIE && !(Ext.isIE7 && Ext.isStrict) && me.getStyle('height') == 'auto'){ 
	            mask.setSize(undefined, me.getHeight());
	        }
	        return mask;
	    },

	    
	    unmask : function(){
		    var me = this,
                dom = me.dom,
		    	mask = data(dom, 'mask'),
		    	maskMsg = data(dom, 'maskMsg');
	        if(mask){
	            if(maskMsg){
	                maskMsg.remove();
                    data(dom, 'maskMsg', undefined);
	            }
	            mask.remove();
                data(dom, 'mask', undefined);
	        }
	        me.removeClass([XMASKED, XMASKEDRELATIVE]);
	    },

	    
	    isMasked : function(){
            var m = data(this.dom, 'mask');
	        return m && m.isVisible();
	    },

	    
	    createShim : function(){
	        var el = document.createElement('iframe'),
	        	shim;
	        el.frameBorder = '0';
	        el.className = 'ext-shim';
	        el.src = Ext.SSL_SECURE_URL;
	        shim = Ext.get(this.dom.parentNode.insertBefore(el, this.dom));
	        shim.autoBoxAdjust = false;
	        return shim;
	    }
    };
}());
Ext.Element.addMethods({
    
    addKeyListener : function(key, fn, scope){
        var config;
        if(typeof key != 'object' || Ext.isArray(key)){
            config = {
                key: key,
                fn: fn,
                scope: scope
            };
        }else{
            config = {
                key : key.key,
                shift : key.shift,
                ctrl : key.ctrl,
                alt : key.alt,
                fn: fn,
                scope: scope
            };
        }
        return new Ext.KeyMap(this, config);
    },

    
    addKeyMap : function(config){
        return new Ext.KeyMap(this, config);
    }
});
(function(){
    
    var NULL = null,
        UNDEFINED = undefined,
        TRUE = true,
        FALSE = false,
        SETX = "setX",
        SETY = "setY",
        SETXY = "setXY",
        LEFT = "left",
        BOTTOM = "bottom",
        TOP = "top",
        RIGHT = "right",
        HEIGHT = "height",
        WIDTH = "width",
        POINTS = "points",
        HIDDEN = "hidden",
        ABSOLUTE = "absolute",
        VISIBLE = "visible",
        MOTION = "motion",
        POSITION = "position",
        EASEOUT = "easeOut",
        
        flyEl = new Ext.Element.Flyweight(),
        queues = {},
        getObject = function(o){
            return o || {};
        },
        fly = function(dom){
            flyEl.dom = dom;
            flyEl.id = Ext.id(dom);
            return flyEl;
        },
        
        getQueue = function(id){
            if(!queues[id]){
                queues[id] = [];
            }
            return queues[id];
        },
        setQueue = function(id, value){
            queues[id] = value;
        };
        

Ext.enableFx = TRUE;


Ext.Fx = {
    
    
    
    switchStatements : function(key, fn, argHash){
        return fn.apply(this, argHash[key]);
    },
    
    
    slideIn : function(anchor, o){ 
        o = getObject(o);
        var me = this,
            dom = me.dom,
            st = dom.style,
            xy,
            r,
            b,              
            wrap,               
            after,
            st,
            args, 
            pt,
            bw,
            bh;
            
        anchor = anchor || "t";

        me.queueFx(o, function(){            
            xy = fly(dom).getXY();
            
            fly(dom).fixDisplay();            
            
            
            r = fly(dom).getFxRestore();      
            b = {x: xy[0], y: xy[1], 0: xy[0], 1: xy[1], width: dom.offsetWidth, height: dom.offsetHeight};
            b.right = b.x + b.width;
            b.bottom = b.y + b.height;
            
            
            fly(dom).setWidth(b.width).setHeight(b.height);            
            
            
            wrap = fly(dom).fxWrap(r.pos, o, HIDDEN);
            
            st.visibility = VISIBLE;
            st.position = ABSOLUTE;
            
            
            function after(){
                 fly(dom).fxUnwrap(wrap, r.pos, o);
                 st.width = r.width;
                 st.height = r.height;
                 fly(dom).afterFx(o);
            }
            
            
            pt = {to: [b.x, b.y]}; 
            bw = {to: b.width};
            bh = {to: b.height};
                
            function argCalc(wrap, style, ww, wh, sXY, sXYval, s1, s2, w, h, p){                    
                var ret = {};
                fly(wrap).setWidth(ww).setHeight(wh);
                if(fly(wrap)[sXY]){
                    fly(wrap)[sXY](sXYval);                  
                }
                style[s1] = style[s2] = "0";                    
                if(w){
                    ret.width = w
                };
                if(h){
                    ret.height = h;
                }
                if(p){
                    ret.points = p;
                }
                return ret;
            };

            args = fly(dom).switchStatements(anchor.toLowerCase(), argCalc, {
                    t  : [wrap, st, b.width, 0, NULL, NULL, LEFT, BOTTOM, NULL, bh, NULL],
                    l  : [wrap, st, 0, b.height, NULL, NULL, RIGHT, TOP, bw, NULL, NULL],
                    r  : [wrap, st, b.width, b.height, SETX, b.right, LEFT, TOP, NULL, NULL, pt],
                    b  : [wrap, st, b.width, b.height, SETY, b.bottom, LEFT, TOP, NULL, bh, pt],
                    tl : [wrap, st, 0, 0, NULL, NULL, RIGHT, BOTTOM, bw, bh, pt],
                    bl : [wrap, st, 0, 0, SETY, b.y + b.height, RIGHT, TOP, bw, bh, pt],
                    br : [wrap, st, 0, 0, SETXY, [b.right, b.bottom], LEFT, TOP, bw, bh, pt],
                    tr : [wrap, st, 0, 0, SETX, b.x + b.width, LEFT, BOTTOM, bw, bh, pt]
                });
            
            st.visibility = VISIBLE;
            fly(wrap).show();

            arguments.callee.anim = fly(wrap).fxanim(args,
                o,
                MOTION,
                .5,
                EASEOUT, 
                after);
        });
        return me;
    },
    
    
    slideOut : function(anchor, o){
        o = getObject(o);
        var me = this,
            dom = me.dom,
            st = dom.style,
            xy = me.getXY(),
            wrap,
            r,
            b,
            a,
            zero = {to: 0}; 
                    
        anchor = anchor || "t";

        me.queueFx(o, function(){
            
            
            r = fly(dom).getFxRestore(); 
            b = {x: xy[0], y: xy[1], 0: xy[0], 1: xy[1], width: dom.offsetWidth, height: dom.offsetHeight};
            b.right = b.x + b.width;
            b.bottom = b.y + b.height;
                
            
            fly(dom).setWidth(b.width).setHeight(b.height);

            
            wrap = fly(dom).fxWrap(r.pos, o, VISIBLE);
                
            st.visibility = VISIBLE;
            st.position = ABSOLUTE;
            fly(wrap).setWidth(b.width).setHeight(b.height);            

            function after(){
                o.useDisplay ? fly(dom).setDisplayed(FALSE) : fly(dom).hide();                
                fly(dom).fxUnwrap(wrap, r.pos, o);
                st.width = r.width;
                st.height = r.height;
                fly(dom).afterFx(o);
            }            
            
            function argCalc(style, s1, s2, p1, v1, p2, v2, p3, v3){                    
                var ret = {};
                
                style[s1] = style[s2] = "0";
                ret[p1] = v1;               
                if(p2){
                    ret[p2] = v2;               
                }
                if(p3){
                    ret[p3] = v3;
                }
                
                return ret;
            };
            
            a = fly(dom).switchStatements(anchor.toLowerCase(), argCalc, {
                t  : [st, LEFT, BOTTOM, HEIGHT, zero],
                l  : [st, RIGHT, TOP, WIDTH, zero],
                r  : [st, LEFT, TOP, WIDTH, zero, POINTS, {to : [b.right, b.y]}],
                b  : [st, LEFT, TOP, HEIGHT, zero, POINTS, {to : [b.x, b.bottom]}],
                tl : [st, RIGHT, BOTTOM, WIDTH, zero, HEIGHT, zero],
                bl : [st, RIGHT, TOP, WIDTH, zero, HEIGHT, zero, POINTS, {to : [b.x, b.bottom]}],
                br : [st, LEFT, TOP, WIDTH, zero, HEIGHT, zero, POINTS, {to : [b.x + b.width, b.bottom]}],
                tr : [st, LEFT, BOTTOM, WIDTH, zero, HEIGHT, zero, POINTS, {to : [b.right, b.y]}]
            });
            
            arguments.callee.anim = fly(wrap).fxanim(a,
                o,
                MOTION,
                .5,
                EASEOUT, 
                after);
        });
        return me;
    },

    
    puff : function(o){
        o = getObject(o);
        var me = this,
            dom = me.dom,
            st = dom.style,
            width,
            height,
            r;

        me.queueFx(o, function(){
            width = fly(dom).getWidth();
            height = fly(dom).getHeight();
            fly(dom).clearOpacity();
            fly(dom).show();

            
            r = fly(dom).getFxRestore();                   
            
            function after(){
                o.useDisplay ? fly(dom).setDisplayed(FALSE) : fly(dom).hide();                  
                fly(dom).clearOpacity();  
                fly(dom).setPositioning(r.pos);
                st.width = r.width;
                st.height = r.height;
                st.fontSize = '';
                fly(dom).afterFx(o);
            }   

            arguments.callee.anim = fly(dom).fxanim({
                    width : {to : fly(dom).adjustWidth(width * 2)},
                    height : {to : fly(dom).adjustHeight(height * 2)},
                    points : {by : [-width * .5, -height * .5]},
                    opacity : {to : 0},
                    fontSize: {to : 200, unit: "%"}
                },
                o,
                MOTION,
                .5,
                EASEOUT,
                 after);
        });
        return me;
    },

    
    switchOff : function(o){
        o = getObject(o);
        var me = this,
            dom = me.dom,
            st = dom.style,
            r;

        me.queueFx(o, function(){
            fly(dom).clearOpacity();
            fly(dom).clip();

            
            r = fly(dom).getFxRestore();
                
            function after(){
                o.useDisplay ? fly(dom).setDisplayed(FALSE) : fly(dom).hide();  
                fly(dom).clearOpacity();
                fly(dom).setPositioning(r.pos);
                st.width = r.width;
                st.height = r.height;   
                fly(dom).afterFx(o);
            };

            fly(dom).fxanim({opacity : {to : 0.3}}, 
                NULL, 
                NULL, 
                .1, 
                NULL, 
                function(){                                 
                    fly(dom).clearOpacity();
                        (function(){                            
                            fly(dom).fxanim({
                                height : {to : 1},
                                points : {by : [0, fly(dom).getHeight() * .5]}
                            }, 
                            o, 
                            MOTION, 
                            0.3, 
                            'easeIn', 
                            after);
                        }).defer(100);
                });
        });
        return me;
    },

     
    highlight : function(color, o){
        o = getObject(o);
        var me = this,
            dom = me.dom,
            attr = o.attr || "backgroundColor",
            a = {},
            restore;

        me.queueFx(o, function(){
            fly(dom).clearOpacity();
            fly(dom).show();

            function after(){
                dom.style[attr] = restore;
                fly(dom).afterFx(o);
            }            
            restore = dom.style[attr];
            a[attr] = {from: color || "ffff9c", to: o.endColor || fly(dom).getColor(attr) || "ffffff"};
            arguments.callee.anim = fly(dom).fxanim(a,
                o,
                'color',
                1,
                'easeIn', 
                after);
        });
        return me;
    },

   
    frame : function(color, count, o){
        o = getObject(o);
        var me = this,
            dom = me.dom,
            proxy,
            active;

        me.queueFx(o, function(){
            color = color || '#C3DAF9';
            if(color.length == 6){
                color = '#' + color;
            }            
            count = count || 1;
            fly(dom).show();

            var xy = fly(dom).getXY(),
                b = {x: xy[0], y: xy[1], 0: xy[0], 1: xy[1], width: dom.offsetWidth, height: dom.offsetHeight},
                queue = function(){
                    proxy = fly(document.body || document.documentElement).createChild({
                        style:{
                            position : ABSOLUTE,
                            'z-index': 35000, 
                            border : '0px solid ' + color
                        }
                    });
                    return proxy.queueFx({}, animFn);
                };
            
            
            arguments.callee.anim = {
                isAnimated: true,
                stop: function() {
                    count = 0;
                    proxy.stopFx();
                }
            };
            
            function animFn(){
                var scale = Ext.isBorderBox ? 2 : 1;
                active = proxy.anim({
                    top : {from : b.y, to : b.y - 20},
                    left : {from : b.x, to : b.x - 20},
                    borderWidth : {from : 0, to : 10},
                    opacity : {from : 1, to : 0},
                    height : {from : b.height, to : b.height + 20 * scale},
                    width : {from : b.width, to : b.width + 20 * scale}
                },{
                    duration: o.duration || 1,
                    callback: function() {
                        proxy.remove();
                        --count > 0 ? queue() : fly(dom).afterFx(o);
                    }
                });
                arguments.callee.anim = {
                    isAnimated: true,
                    stop: function(){
                        active.stop();
                    }
                };
            };
            queue();
        });
        return me;
    },

   
    pause : function(seconds){        
        var dom = this.dom,
            t;

        this.queueFx({}, function(){
            t = setTimeout(function(){
                fly(dom).afterFx({});
            }, seconds * 1000);
            arguments.callee.anim = {
                isAnimated: true,
                stop: function(){
                    clearTimeout(t);
                    fly(dom).afterFx({});
                }
            };
        });
        return this;
    },

   
    fadeIn : function(o){
        o = getObject(o);
        var me = this,
            dom = me.dom,
            to = o.endOpacity || 1;
        
        me.queueFx(o, function(){
            fly(dom).setOpacity(0);
            fly(dom).fixDisplay();
            dom.style.visibility = VISIBLE;
            arguments.callee.anim = fly(dom).fxanim({opacity:{to:to}},
                o, NULL, .5, EASEOUT, function(){
                if(to == 1){
                    fly(dom).clearOpacity();
                }
                fly(dom).afterFx(o);
            });
        });
        return me;
    },

   
    fadeOut : function(o){
        o = getObject(o);
        var me = this,
            dom = me.dom,
            style = dom.style,
            to = o.endOpacity || 0;         
        
        me.queueFx(o, function(){  
            arguments.callee.anim = fly(dom).fxanim({ 
                opacity : {to : to}},
                o, 
                NULL, 
                .5, 
                EASEOUT, 
                function(){
                    if(to == 0){
                        Ext.Element.data(dom, 'visibilityMode') == Ext.Element.DISPLAY || o.useDisplay ? 
                            style.display = "none" :
                            style.visibility = HIDDEN;
                            
                        fly(dom).clearOpacity();
                    }
                    fly(dom).afterFx(o);
            });
        });
        return me;
    },

   
    scale : function(w, h, o){
        this.shift(Ext.apply({}, o, {
            width: w,
            height: h
        }));
        return this;
    },

   
    shift : function(o){
        o = getObject(o);
        var dom = this.dom,
            a = {};
                
        this.queueFx(o, function(){
            for (var prop in o) {
                if (o[prop] != UNDEFINED) {                                                 
                    a[prop] = {to : o[prop]};                   
                }
            } 
            
            a.width ? a.width.to = fly(dom).adjustWidth(o.width) : a;
            a.height ? a.height.to = fly(dom).adjustWidth(o.height) : a;   
            
            if (a.x || a.y || a.xy) {
                a.points = a.xy || 
                           {to : [ a.x ? a.x.to : fly(dom).getX(),
                                   a.y ? a.y.to : fly(dom).getY()]};                  
            }

            arguments.callee.anim = fly(dom).fxanim(a,
                o, 
                MOTION, 
                .35, 
                EASEOUT, 
                function(){
                    fly(dom).afterFx(o);
                });
        });
        return this;
    },

    
    ghost : function(anchor, o){
        o = getObject(o);
        var me = this,
            dom = me.dom,
            st = dom.style,
            a = {opacity: {to: 0}, points: {}},
            pt = a.points,
            r,
            w,
            h;
            
        anchor = anchor || "b";

        me.queueFx(o, function(){
            
            r = fly(dom).getFxRestore();
            w = fly(dom).getWidth();
            h = fly(dom).getHeight();
            
            function after(){
                o.useDisplay ? fly(dom).setDisplayed(FALSE) : fly(dom).hide();   
                fly(dom).clearOpacity();
                fly(dom).setPositioning(r.pos);
                st.width = r.width;
                st.height = r.height;
                fly(dom).afterFx(o);
            }
                
            pt.by = fly(dom).switchStatements(anchor.toLowerCase(), function(v1,v2){ return [v1, v2];}, {
               t  : [0, -h],
               l  : [-w, 0],
               r  : [w, 0],
               b  : [0, h],
               tl : [-w, -h],
               bl : [-w, h],
               br : [w, h],
               tr : [w, -h] 
            });
                
            arguments.callee.anim = fly(dom).fxanim(a,
                o,
                MOTION,
                .5,
                EASEOUT, after);
        });
        return me;
    },

    
    syncFx : function(){
        var me = this;
        me.fxDefaults = Ext.apply(me.fxDefaults || {}, {
            block : FALSE,
            concurrent : TRUE,
            stopFx : FALSE
        });
        return me;
    },

    
    sequenceFx : function(){
        var me = this;
        me.fxDefaults = Ext.apply(me.fxDefaults || {}, {
            block : FALSE,
            concurrent : FALSE,
            stopFx : FALSE
        });
        return me;
    },

    
    nextFx : function(){        
        var ef = getQueue(this.dom.id)[0];
        if(ef){
            ef.call(this);
        }
    },

    
    hasActiveFx : function(){
        return getQueue(this.dom.id)[0];
    },

    
    stopFx : function(finish){
        var me = this,
            id = me.dom.id;
        if(me.hasActiveFx()){
            var cur = getQueue(id)[0];
            if(cur && cur.anim){
                if(cur.anim.isAnimated){
                    setQueue(id, [cur]); 
                    cur.anim.stop(finish !== undefined ? finish : TRUE);
                }else{
                    setQueue(id, []);
                }
            }
        }
        return me;
    },

    
    beforeFx : function(o){
        if(this.hasActiveFx() && !o.concurrent){
           if(o.stopFx){
               this.stopFx();
               return TRUE;
           }
           return FALSE;
        }
        return TRUE;
    },

    
    hasFxBlock : function(){
        var q = getQueue(this.dom.id);
        return q && q[0] && q[0].block;
    },

    
    queueFx : function(o, fn){
        var me = fly(this.dom);
        if(!me.hasFxBlock()){
            Ext.applyIf(o, me.fxDefaults);
            if(!o.concurrent){
                var run = me.beforeFx(o);
                fn.block = o.block;
                getQueue(me.dom.id).push(fn);
                if(run){
                    me.nextFx();
                }
            }else{
                fn.call(me);
            }
        }
        return me;
    },

    
    fxWrap : function(pos, o, vis){ 
        var dom = this.dom,
            wrap,
            wrapXY;
        if(!o.wrap || !(wrap = Ext.getDom(o.wrap))){            
            if(o.fixPosition){
                wrapXY = fly(dom).getXY();
            }
            var div = document.createElement("div");
            div.style.visibility = vis;
            wrap = dom.parentNode.insertBefore(div, dom);
            fly(wrap).setPositioning(pos);
            if(fly(wrap).isStyle(POSITION, "static")){
                fly(wrap).position("relative");
            }
            fly(dom).clearPositioning('auto');
            fly(wrap).clip();
            wrap.appendChild(dom);
            if(wrapXY){
                fly(wrap).setXY(wrapXY);
            }
        }
        return wrap;
    },

    
    fxUnwrap : function(wrap, pos, o){      
        var dom = this.dom;
        fly(dom).clearPositioning();
        fly(dom).setPositioning(pos);
        if(!o.wrap){
            var pn = fly(wrap).dom.parentNode;
            pn.insertBefore(dom, wrap); 
            fly(wrap).remove();
        }
    },

    
    getFxRestore : function(){
        var st = this.dom.style;
        return {pos: this.getPositioning(), width: st.width, height : st.height};
    },

    
    afterFx : function(o){
        var dom = this.dom,
            id = dom.id;
        if(o.afterStyle){
            fly(dom).setStyle(o.afterStyle);            
        }
        if(o.afterCls){
            fly(dom).addClass(o.afterCls);
        }
        if(o.remove == TRUE){
            fly(dom).remove();
        }
        if(o.callback){
            o.callback.call(o.scope, fly(dom));
        }
        if(!o.concurrent){
            getQueue(id).shift();
            fly(dom).nextFx();
        }
    },

    
    fxanim : function(args, opt, animType, defaultDur, defaultEase, cb){
        animType = animType || 'run';
        opt = opt || {};
        var anim = Ext.lib.Anim[animType](
                this.dom, 
                args,
                (opt.duration || defaultDur) || .35,
                (opt.easing || defaultEase) || EASEOUT,
                cb,            
                this
            );
        opt.anim = anim;
        return anim;
    }
};


Ext.Fx.resize = Ext.Fx.scale;



Ext.Element.addMethods(Ext.Fx);
})();

Ext.CompositeElementLite = function(els, root){
    
    this.elements = [];
    this.add(els, root);
    this.el = new Ext.Element.Flyweight();
};

Ext.CompositeElementLite.prototype = {
    isComposite: true,

    
    getElement : function(el){
        
        var e = this.el;
        e.dom = el;
        e.id = el.id;
        return e;
    },

    
    transformElement : function(el){
        return Ext.getDom(el);
    },

    
    getCount : function(){
        return this.elements.length;
    },
    
    add : function(els, root){
        var me = this,
            elements = me.elements;
        if(!els){
            return this;
        }
        if(typeof els == "string"){
            els = Ext.Element.selectorFunction(els, root);
        }else if(els.isComposite){
            els = els.elements;
        }else if(!Ext.isIterable(els)){
            els = [els];
        }

        for(var i = 0, len = els.length; i < len; ++i){
            elements.push(me.transformElement(els[i]));
        }
        return me;
    },

    invoke : function(fn, args){
        var me = this,
            els = me.elements,
            len = els.length,
            e,
            i;

        for(i = 0; i < len; i++) {
            e = els[i];
            if(e){
                Ext.Element.prototype[fn].apply(me.getElement(e), args);
            }
        }
        return me;
    },
    
    item : function(index){
        var me = this,
            el = me.elements[index],
            out = null;

        if(el){
            out = me.getElement(el);
        }
        return out;
    },

    
    addListener : function(eventName, handler, scope, opt){
        var els = this.elements,
            len = els.length,
            i, e;

        for(i = 0; i<len; i++) {
            e = els[i];
            if(e) {
                Ext.EventManager.on(e, eventName, handler, scope || e, opt);
            }
        }
        return this;
    },
    
    each : function(fn, scope){
        var me = this,
            els = me.elements,
            len = els.length,
            i, e;

        for(i = 0; i<len; i++) {
            e = els[i];
            if(e){
                e = this.getElement(e);
                if(fn.call(scope || e, e, me, i) === false){
                    break;
                }
            }
        }
        return me;
    },

    
    fill : function(els){
        var me = this;
        me.elements = [];
        me.add(els);
        return me;
    },

    
    filter : function(selector){
        var els = [],
            me = this,
            elements = me.elements,
            fn = Ext.isFunction(selector) ? selector
                : function(el){
                    return el.is(selector);
                };


        me.each(function(el, self, i){
            if(fn(el, i) !== false){
                els[els.length] = me.transformElement(el);
            }
        });
        me.elements = els;
        return me;
    },

    
    indexOf : function(el){
        return this.elements.indexOf(this.transformElement(el));
    },

    
    replaceElement : function(el, replacement, domReplace){
        var index = !isNaN(el) ? el : this.indexOf(el),
            d;
        if(index > -1){
            replacement = Ext.getDom(replacement);
            if(domReplace){
                d = this.elements[index];
                d.parentNode.insertBefore(replacement, d);
                Ext.removeNode(d);
            }
            this.elements.splice(index, 1, replacement);
        }
        return this;
    },

    
    clear : function(){
        this.elements = [];
    }
};

Ext.CompositeElementLite.prototype.on = Ext.CompositeElementLite.prototype.addListener;

(function(){
var fnName,
    ElProto = Ext.Element.prototype,
    CelProto = Ext.CompositeElementLite.prototype;

for(fnName in ElProto){
    if(Ext.isFunction(ElProto[fnName])){
        (function(fnName){
            CelProto[fnName] = CelProto[fnName] || function(){
                return this.invoke(fnName, arguments);
            };
        }).call(CelProto, fnName);

    }
}
})();

if(Ext.DomQuery){
    Ext.Element.selectorFunction = Ext.DomQuery.select;
}


Ext.Element.select = function(selector, root){
    var els;
    if(typeof selector == "string"){
        els = Ext.Element.selectorFunction(selector, root);
    }else if(selector.length !== undefined){
        els = selector;
    }else{
        throw "Invalid selector";
    }
    return new Ext.CompositeElementLite(els);
};

Ext.select = Ext.Element.select;

Ext.apply(Ext.CompositeElementLite.prototype, {
    addElements : function(els, root){
        if(!els){
            return this;
        }
        if(typeof els == "string"){
            els = Ext.Element.selectorFunction(els, root);
        }
        var yels = this.elements;
        Ext.each(els, function(e) {
            yels.push(Ext.get(e));
        });
        return this;
    },

    
    first : function(){
        return this.item(0);
    },

    
    last : function(){
        return this.item(this.getCount()-1);
    },

    
    contains : function(el){
        return this.indexOf(el) != -1;
    },

    
    removeElement : function(keys, removeDom){
        var me = this,
            els = this.elements,
            el;
        Ext.each(keys, function(val){
            if ((el = (els[val] || els[val = me.indexOf(val)]))) {
                if(removeDom){
                    if(el.dom){
                        el.remove();
                    }else{
                        Ext.removeNode(el);
                    }
                }
                els.splice(val, 1);
            }
        });
        return this;
    }
});

Ext.CompositeElement = Ext.extend(Ext.CompositeElementLite, {
    
    constructor : function(els, root){
        this.elements = [];
        this.add(els, root);
    },
    
    
    getElement : function(el){
        
        return el;
    },
    
    
    transformElement : function(el){
        return Ext.get(el);
    }

    

    

    
});


Ext.Element.select = function(selector, unique, root){
    var els;
    if(typeof selector == "string"){
        els = Ext.Element.selectorFunction(selector, root);
    }else if(selector.length !== undefined){
        els = selector;
    }else{
        throw "Invalid selector";
    }

    return (unique === true) ? new Ext.CompositeElement(els) : new Ext.CompositeElementLite(els);
};


Ext.select = Ext.Element.select;(function(){
    var BEFOREREQUEST = "beforerequest",
        REQUESTCOMPLETE = "requestcomplete",
        REQUESTEXCEPTION = "requestexception",
        UNDEFINED = undefined,
        LOAD = 'load',
        POST = 'POST',
        GET = 'GET',
        WINDOW = window;

    
    Ext.data.Connection = function(config){
        Ext.apply(this, config);
        this.addEvents(
            
            BEFOREREQUEST,
            
            REQUESTCOMPLETE,
            
            REQUESTEXCEPTION
        );
        Ext.data.Connection.superclass.constructor.call(this);
    };

    Ext.extend(Ext.data.Connection, Ext.util.Observable, {
        
        
        
        
        
        timeout : 30000,
        
        autoAbort:false,

        
        disableCaching: true,

        
        disableCachingParam: '_dc',

        
        request : function(o){
            var me = this;
            if(me.fireEvent(BEFOREREQUEST, me, o)){
                if (o.el) {
                    if(!Ext.isEmpty(o.indicatorText)){
                        me.indicatorText = '<div class="loading-indicator">'+o.indicatorText+"</div>";
                    }
                    if(me.indicatorText) {
                        Ext.getDom(o.el).innerHTML = me.indicatorText;
                    }
                    o.success = (Ext.isFunction(o.success) ? o.success : function(){}).createInterceptor(function(response) {
                        Ext.getDom(o.el).innerHTML = response.responseText;
                    });
                }

                var p = o.params,
                    url = o.url || me.url,
                    method,
                    cb = {success: me.handleResponse,
                          failure: me.handleFailure,
                          scope: me,
                          argument: {options: o},
                          timeout : o.timeout || me.timeout
                    },
                    form,
                    serForm;


                if (Ext.isFunction(p)) {
                    p = p.call(o.scope||WINDOW, o);
                }

                p = Ext.urlEncode(me.extraParams, Ext.isObject(p) ? Ext.urlEncode(p) : p);

                if (Ext.isFunction(url)) {
                    url = url.call(o.scope || WINDOW, o);
                }

                if((form = Ext.getDom(o.form))){
                    url = url || form.action;
                     if(o.isUpload || /multipart\/form-data/i.test(form.getAttribute("enctype"))) {
                         return me.doFormUpload.call(me, o, p, url);
                     }
                    serForm = Ext.lib.Ajax.serializeForm(form);
                    p = p ? (p + '&' + serForm) : serForm;
                }

                method = o.method || me.method || ((p || o.xmlData || o.jsonData) ? POST : GET);

                if(method === GET && (me.disableCaching && o.disableCaching !== false) || o.disableCaching === true){
                    var dcp = o.disableCachingParam || me.disableCachingParam;
                    url = Ext.urlAppend(url, dcp + '=' + (new Date().getTime()));
                }

                o.headers = Ext.apply(o.headers || {}, me.defaultHeaders || {});

                if(o.autoAbort === true || me.autoAbort) {
                    me.abort();
                }

                if((method == GET || o.xmlData || o.jsonData) && p){
                    url = Ext.urlAppend(url, p);
                    p = '';
                }
                return (me.transId = Ext.lib.Ajax.request(method, url, cb, p, o));
            }else{
                return o.callback ? o.callback.apply(o.scope, [o,UNDEFINED,UNDEFINED]) : null;
            }
        },

        
        isLoading : function(transId){
            return transId ? Ext.lib.Ajax.isCallInProgress(transId) : !! this.transId;
        },

        
        abort : function(transId){
            if(transId || this.isLoading()){
                Ext.lib.Ajax.abort(transId || this.transId);
            }
        },

        
        handleResponse : function(response){
            this.transId = false;
            var options = response.argument.options;
            response.argument = options ? options.argument : null;
            this.fireEvent(REQUESTCOMPLETE, this, response, options);
            if(options.success){
                options.success.call(options.scope, response, options);
            }
            if(options.callback){
                options.callback.call(options.scope, options, true, response);
            }
        },

        
        handleFailure : function(response, e){
            this.transId = false;
            var options = response.argument.options;
            response.argument = options ? options.argument : null;
            this.fireEvent(REQUESTEXCEPTION, this, response, options, e);
            if(options.failure){
                options.failure.call(options.scope, response, options);
            }
            if(options.callback){
                options.callback.call(options.scope, options, false, response);
            }
        },

        
        doFormUpload : function(o, ps, url){
            var id = Ext.id(),
                doc = document,
                frame = doc.createElement('iframe'),
                form = Ext.getDom(o.form),
                hiddens = [],
                hd,
                encoding = 'multipart/form-data',
                buf = {
                    target: form.target,
                    method: form.method,
                    encoding: form.encoding,
                    enctype: form.enctype,
                    action: form.action
                };

            
            Ext.fly(frame).set({
                id: id,
                name: id,
                cls: 'x-hidden',
                src: Ext.SSL_SECURE_URL
            }); 

            doc.body.appendChild(frame);

            
            if(Ext.isIE){
               document.frames[id].name = id;
            }


            Ext.fly(form).set({
                target: id,
                method: POST,
                enctype: encoding,
                encoding: encoding,
                action: url || buf.action
            });

            
            Ext.iterate(Ext.urlDecode(ps, false), function(k, v){
                hd = doc.createElement('input');
                Ext.fly(hd).set({
                    type: 'hidden',
                    value: v,
                    name: k
                });
                form.appendChild(hd);
                hiddens.push(hd);
            });

            function cb(){
                var me = this,
                    
                    r = {responseText : '',
                         responseXML : null,
                         argument : o.argument},
                    doc,
                    firstChild;

                try{
                    doc = frame.contentWindow.document || frame.contentDocument || WINDOW.frames[id].document;
                    if(doc){
                        if(doc.body){
                            if(/textarea/i.test((firstChild = doc.body.firstChild || {}).tagName)){ 
                                r.responseText = firstChild.value;
                            }else{
                                r.responseText = doc.body.innerHTML;
                            }
                        }
                        
                        r.responseXML = doc.XMLDocument || doc;
                    }
                }
                catch(e) {}

                Ext.EventManager.removeListener(frame, LOAD, cb, me);

                me.fireEvent(REQUESTCOMPLETE, me, r, o);

                function runCallback(fn, scope, args){
                    if(Ext.isFunction(fn)){
                        fn.apply(scope, args);
                    }
                }

                runCallback(o.success, o.scope, [r, o]);
                runCallback(o.callback, o.scope, [o, true, r]);

                if(!me.debugUploads){
                    setTimeout(function(){Ext.removeNode(frame);}, 100);
                }
            }

            Ext.EventManager.on(frame, LOAD, cb, this);
            form.submit();

            Ext.fly(form).set(buf);
            Ext.each(hiddens, function(h) {
                Ext.removeNode(h);
            });
        }
    });
})();


Ext.Ajax = new Ext.data.Connection({
    
    
    
    
    
    

    

    
    
    
    
    
    

    
    autoAbort : false,

    
    serializeForm : function(form){
        return Ext.lib.Ajax.serializeForm(form);
    }
});

Ext.UpdateManager = Ext.Updater = Ext.extend(Ext.util.Observable,
function() {
    var BEFOREUPDATE = "beforeupdate",
        UPDATE = "update",
        FAILURE = "failure";

    
    function processSuccess(response){
        var me = this;
        me.transaction = null;
        if (response.argument.form && response.argument.reset) {
            try { 
                response.argument.form.reset();
            } catch(e){}
        }
        if (me.loadScripts) {
            me.renderer.render(me.el, response, me,
               updateComplete.createDelegate(me, [response]));
        } else {
            me.renderer.render(me.el, response, me);
            updateComplete.call(me, response);
        }
    }

    
    function updateComplete(response, type, success){
        this.fireEvent(type || UPDATE, this.el, response);
        if(Ext.isFunction(response.argument.callback)){
            response.argument.callback.call(response.argument.scope, this.el, Ext.isEmpty(success) ? true : false, response, response.argument.options);
        }
    }

    
    function processFailure(response){
        updateComplete.call(this, response, FAILURE, !!(this.transaction = null));
    }

    return {
        constructor: function(el, forceNew){
            var me = this;
            el = Ext.get(el);
            if(!forceNew && el.updateManager){
                return el.updateManager;
            }
            
            me.el = el;
            
            me.defaultUrl = null;

            me.addEvents(
                
                BEFOREUPDATE,
                
                UPDATE,
                
                FAILURE
            );

            Ext.apply(me, Ext.Updater.defaults);
            
            
            
            
            
            

            
            me.transaction = null;
            
            me.refreshDelegate = me.refresh.createDelegate(me);
            
            me.updateDelegate = me.update.createDelegate(me);
            
            me.formUpdateDelegate = (me.formUpdate || function(){}).createDelegate(me);

            
            me.renderer = me.renderer || me.getDefaultRenderer();

            Ext.Updater.superclass.constructor.call(me);
        },

        
        setRenderer : function(renderer){
            this.renderer = renderer;
        },

        
        getRenderer : function(){
           return this.renderer;
        },

        
        getDefaultRenderer: function() {
            return new Ext.Updater.BasicRenderer();
        },

        
        setDefaultUrl : function(defaultUrl){
            this.defaultUrl = defaultUrl;
        },

        
        getEl : function(){
            return this.el;
        },

        
        update : function(url, params, callback, discardUrl){
            var me = this,
                cfg,
                callerScope;

            if(me.fireEvent(BEFOREUPDATE, me.el, url, params) !== false){
                if(Ext.isObject(url)){ 
                    cfg = url;
                    url = cfg.url;
                    params = params || cfg.params;
                    callback = callback || cfg.callback;
                    discardUrl = discardUrl || cfg.discardUrl;
                    callerScope = cfg.scope;
                    if(!Ext.isEmpty(cfg.nocache)){me.disableCaching = cfg.nocache;};
                    if(!Ext.isEmpty(cfg.text)){me.indicatorText = '<div class="loading-indicator">'+cfg.text+"</div>";};
                    if(!Ext.isEmpty(cfg.scripts)){me.loadScripts = cfg.scripts;};
                    if(!Ext.isEmpty(cfg.timeout)){me.timeout = cfg.timeout;};
                }
                me.showLoading();

                if(!discardUrl){
                    me.defaultUrl = url;
                }
                if(Ext.isFunction(url)){
                    url = url.call(me);
                }

                var o = Ext.apply({}, {
                    url : url,
                    params: (Ext.isFunction(params) && callerScope) ? params.createDelegate(callerScope) : params,
                    success: processSuccess,
                    failure: processFailure,
                    scope: me,
                    callback: undefined,
                    timeout: (me.timeout*1000),
                    disableCaching: me.disableCaching,
                    argument: {
                        "options": cfg,
                        "url": url,
                        "form": null,
                        "callback": callback,
                        "scope": callerScope || window,
                        "params": params
                    }
                }, cfg);

                me.transaction = Ext.Ajax.request(o);
            }
        },

        
        formUpdate : function(form, url, reset, callback){
            var me = this;
            if(me.fireEvent(BEFOREUPDATE, me.el, form, url) !== false){
                if(Ext.isFunction(url)){
                    url = url.call(me);
                }
                form = Ext.getDom(form);
                me.transaction = Ext.Ajax.request({
                    form: form,
                    url:url,
                    success: processSuccess,
                    failure: processFailure,
                    scope: me,
                    timeout: (me.timeout*1000),
                    argument: {
                        "url": url,
                        "form": form,
                        "callback": callback,
                        "reset": reset
                    }
                });
                me.showLoading.defer(1, me);
            }
        },

        
        startAutoRefresh : function(interval, url, params, callback, refreshNow){
            var me = this;
            if(refreshNow){
                me.update(url || me.defaultUrl, params, callback, true);
            }
            if(me.autoRefreshProcId){
                clearInterval(me.autoRefreshProcId);
            }
            me.autoRefreshProcId = setInterval(me.update.createDelegate(me, [url || me.defaultUrl, params, callback, true]), interval * 1000);
        },

        
        stopAutoRefresh : function(){
            if(this.autoRefreshProcId){
                clearInterval(this.autoRefreshProcId);
                delete this.autoRefreshProcId;
            }
        },

        
        isAutoRefreshing : function(){
           return !!this.autoRefreshProcId;
        },

        
        showLoading : function(){
            if(this.showLoadIndicator){
                this.el.dom.innerHTML = this.indicatorText;
            }
        },

        
        abort : function(){
            if(this.transaction){
                Ext.Ajax.abort(this.transaction);
            }
        },

        
        isUpdating : function(){
            return this.transaction ? Ext.Ajax.isLoading(this.transaction) : false;
        },

        
        refresh : function(callback){
            if(this.defaultUrl){
                this.update(this.defaultUrl, null, callback, true);
            }
        }
    }
}());


Ext.Updater.defaults = {
   
    timeout : 30,
    
    disableCaching : false,
    
    showLoadIndicator : true,
    
    indicatorText : '<div class="loading-indicator">Loading...</div>',
     
    loadScripts : false,
    
    sslBlankUrl : Ext.SSL_SECURE_URL
};



Ext.Updater.updateElement = function(el, url, params, options){
    var um = Ext.get(el).getUpdater();
    Ext.apply(um, options);
    um.update(url, params, options ? options.callback : null);
};


Ext.Updater.BasicRenderer = function(){};

Ext.Updater.BasicRenderer.prototype = {
    
     render : function(el, response, updateManager, callback){
        el.update(response.responseText, updateManager.loadScripts, callback);
    }
};



(function() {


Date.useStrict = false;





function xf(format) {
    var args = Array.prototype.slice.call(arguments, 1);
    return format.replace(/\{(\d+)\}/g, function(m, i) {
        return args[i];
    });
}



Date.formatCodeToRegex = function(character, currentGroup) {
    
    var p = Date.parseCodes[character];

    if (p) {
      p = typeof p == 'function'? p() : p;
      Date.parseCodes[character] = p; 
    }

    return p ? Ext.applyIf({
      c: p.c ? xf(p.c, currentGroup || "{0}") : p.c
    }, p) : {
        g:0,
        c:null,
        s:Ext.escapeRe(character) 
    }
};


var $f = Date.formatCodeToRegex;

Ext.apply(Date, {
    
    parseFunctions: {
        "M$": function(input, strict) {
            
            
            var re = new RegExp('\\/Date\\(([-+])?(\\d+)(?:[+-]\\d{4})?\\)\\/');
            var r = (input || '').match(re);
            return r? new Date(((r[1] || '') + r[2]) * 1) : null;
        }
    },
    parseRegexes: [],

    
    formatFunctions: {
        "M$": function() {
            
            return '\\/Date(' + this.getTime() + ')\\/';
        }
    },

    y2kYear : 50,

    
    MILLI : "ms",

    
    SECOND : "s",

    
    MINUTE : "mi",

    
    HOUR : "h",

    
    DAY : "d",

    
    MONTH : "mo",

    
    YEAR : "y",

    
    defaults: {},

    
    dayNames : [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday"
    ],

    
    monthNames : [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December"
    ],

    
    monthNumbers : {
        Jan:0,
        Feb:1,
        Mar:2,
        Apr:3,
        May:4,
        Jun:5,
        Jul:6,
        Aug:7,
        Sep:8,
        Oct:9,
        Nov:10,
        Dec:11
    },

    
    getShortMonthName : function(month) {
        return Date.monthNames[month].substring(0, 3);
    },

    
    getShortDayName : function(day) {
        return Date.dayNames[day].substring(0, 3);
    },

    
    getMonthNumber : function(name) {
        
        return Date.monthNumbers[name.substring(0, 1).toUpperCase() + name.substring(1, 3).toLowerCase()];
    },

    
    formatCodes : {
        d: "String.leftPad(this.getDate(), 2, '0')",
        D: "Date.getShortDayName(this.getDay())", 
        j: "this.getDate()",
        l: "Date.dayNames[this.getDay()]",
        N: "(this.getDay() ? this.getDay() : 7)",
        S: "this.getSuffix()",
        w: "this.getDay()",
        z: "this.getDayOfYear()",
        W: "String.leftPad(this.getWeekOfYear(), 2, '0')",
        F: "Date.monthNames[this.getMonth()]",
        m: "String.leftPad(this.getMonth() + 1, 2, '0')",
        M: "Date.getShortMonthName(this.getMonth())", 
        n: "(this.getMonth() + 1)",
        t: "this.getDaysInMonth()",
        L: "(this.isLeapYear() ? 1 : 0)",
        o: "(this.getFullYear() + (this.getWeekOfYear() == 1 && this.getMonth() > 0 ? +1 : (this.getWeekOfYear() >= 52 && this.getMonth() < 11 ? -1 : 0)))",
        Y: "this.getFullYear()",
        y: "('' + this.getFullYear()).substring(2, 4)",
        a: "(this.getHours() < 12 ? 'am' : 'pm')",
        A: "(this.getHours() < 12 ? 'AM' : 'PM')",
        g: "((this.getHours() % 12) ? this.getHours() % 12 : 12)",
        G: "this.getHours()",
        h: "String.leftPad((this.getHours() % 12) ? this.getHours() % 12 : 12, 2, '0')",
        H: "String.leftPad(this.getHours(), 2, '0')",
        i: "String.leftPad(this.getMinutes(), 2, '0')",
        s: "String.leftPad(this.getSeconds(), 2, '0')",
        u: "String.leftPad(this.getMilliseconds(), 3, '0')",
        O: "this.getGMTOffset()",
        P: "this.getGMTOffset(true)",
        T: "this.getTimezone()",
        Z: "(this.getTimezoneOffset() * -60)",

        c: function() { 
            for (var c = "Y-m-dTH:i:sP", code = [], i = 0, l = c.length; i < l; ++i) {
                var e = c.charAt(i);
                code.push(e == "T" ? "'T'" : Date.getFormatCode(e)); 
            }
            return code.join(" + ");
        },
        

        U: "Math.round(this.getTime() / 1000)"
    },

    
    isValid : function(y, m, d, h, i, s, ms) {
        
        h = h || 0;
        i = i || 0;
        s = s || 0;
        ms = ms || 0;

        var dt = new Date(y, m - 1, d, h, i, s, ms);

        return y == dt.getFullYear() &&
            m == dt.getMonth() + 1 &&
            d == dt.getDate() &&
            h == dt.getHours() &&
            i == dt.getMinutes() &&
            s == dt.getSeconds() &&
            ms == dt.getMilliseconds();
    },

    
    parseDate : function(input, format, strict) {
        var p = Date.parseFunctions;
        if (p[format] == null) {
            Date.createParser(format);
        }
        return p[format](input, Ext.isDefined(strict) ? strict : Date.useStrict);
    },

    
    getFormatCode : function(character) {
        var f = Date.formatCodes[character];

        if (f) {
          f = typeof f == 'function'? f() : f;
          Date.formatCodes[character] = f; 
        }

        
        return f || ("'" + String.escape(character) + "'");
    },

    
    createFormat : function(format) {
        var code = [],
            special = false,
            ch = '';

        for (var i = 0; i < format.length; ++i) {
            ch = format.charAt(i);
            if (!special && ch == "\\") {
                special = true;
            } else if (special) {
                special = false;
                code.push("'" + String.escape(ch) + "'");
            } else {
                code.push(Date.getFormatCode(ch))
            }
        }
        Date.formatFunctions[format] = new Function("return " + code.join('+'));
    },

    
    createParser : function() {
        var code = [
            "var dt, y, m, d, h, i, s, ms, o, z, zz, u, v,",
                "def = Date.defaults,",
                "results = String(input).match(Date.parseRegexes[{0}]);", 

            "if(results){",
                "{1}",

                "if(u != null){", 
                    "v = new Date(u * 1000);", 
                "}else{",
                    
                    
                    
                    "dt = (new Date()).clearTime();",

                    
                    "y = Ext.num(y, Ext.num(def.y, dt.getFullYear()));",
                    "m = Ext.num(m, Ext.num(def.m - 1, dt.getMonth()));",
                    "d = Ext.num(d, Ext.num(def.d, dt.getDate()));",

                    
                    "h  = Ext.num(h, Ext.num(def.h, dt.getHours()));",
                    "i  = Ext.num(i, Ext.num(def.i, dt.getMinutes()));",
                    "s  = Ext.num(s, Ext.num(def.s, dt.getSeconds()));",
                    "ms = Ext.num(ms, Ext.num(def.ms, dt.getMilliseconds()));",

                    "if(z >= 0 && y >= 0){",
                        
                        

                        
                        "v = new Date(y, 0, 1, h, i, s, ms);",

                        
                        "v = !strict? v : (strict === true && (z <= 364 || (v.isLeapYear() && z <= 365))? v.add(Date.DAY, z) : null);",
                    "}else if(strict === true && !Date.isValid(y, m + 1, d, h, i, s, ms)){", 
                        "v = null;", 
                    "}else{",
                        
                        "v = new Date(y, m, d, h, i, s, ms);",
                    "}",
                "}",
            "}",

            "if(v){",
                
                "if(zz != null){",
                    
                    "v = v.add(Date.SECOND, -v.getTimezoneOffset() * 60 - zz);",
                "}else if(o){",
                    
                    "v = v.add(Date.MINUTE, -v.getTimezoneOffset() + (sn == '+'? -1 : 1) * (hr * 60 + mn));",
                "}",
            "}",

            "return v;"
        ].join('\n');

        return function(format) {
            var regexNum = Date.parseRegexes.length,
                currentGroup = 1,
                calc = [],
                regex = [],
                special = false,
                ch = "";

            for (var i = 0; i < format.length; ++i) {
                ch = format.charAt(i);
                if (!special && ch == "\\") {
                    special = true;
                } else if (special) {
                    special = false;
                    regex.push(String.escape(ch));
                } else {
                    var obj = $f(ch, currentGroup);
                    currentGroup += obj.g;
                    regex.push(obj.s);
                    if (obj.g && obj.c) {
                        calc.push(obj.c);
                    }
                }
            }

            Date.parseRegexes[regexNum] = new RegExp("^" + regex.join('') + "$");
            Date.parseFunctions[format] = new Function("input", "strict", xf(code, regexNum, calc.join('')));
        }
    }(),

    
    parseCodes : {
        
        d: {
            g:1,
            c:"d = parseInt(results[{0}], 10);\n",
            s:"(\\d{2})" 
        },
        j: {
            g:1,
            c:"d = parseInt(results[{0}], 10);\n",
            s:"(\\d{1,2})" 
        },
        D: function() {
            for (var a = [], i = 0; i < 7; a.push(Date.getShortDayName(i)), ++i); 
            return {
                g:0,
                c:null,
                s:"(?:" + a.join("|") +")"
            }
        },
        l: function() {
            return {
                g:0,
                c:null,
                s:"(?:" + Date.dayNames.join("|") + ")"
            }
        },
        N: {
            g:0,
            c:null,
            s:"[1-7]" 
        },
        S: {
            g:0,
            c:null,
            s:"(?:st|nd|rd|th)"
        },
        w: {
            g:0,
            c:null,
            s:"[0-6]" 
        },
        z: {
            g:1,
            c:"z = parseInt(results[{0}], 10);\n",
            s:"(\\d{1,3})" 
        },
        W: {
            g:0,
            c:null,
            s:"(?:\\d{2})" 
        },
        F: function() {
            return {
                g:1,
                c:"m = parseInt(Date.getMonthNumber(results[{0}]), 10);\n", 
                s:"(" + Date.monthNames.join("|") + ")"
            }
        },
        M: function() {
            for (var a = [], i = 0; i < 12; a.push(Date.getShortMonthName(i)), ++i); 
            return Ext.applyIf({
                s:"(" + a.join("|") + ")"
            }, $f("F"));
        },
        m: {
            g:1,
            c:"m = parseInt(results[{0}], 10) - 1;\n",
            s:"(\\d{2})" 
        },
        n: {
            g:1,
            c:"m = parseInt(results[{0}], 10) - 1;\n",
            s:"(\\d{1,2})" 
        },
        t: {
            g:0,
            c:null,
            s:"(?:\\d{2})" 
        },
        L: {
            g:0,
            c:null,
            s:"(?:1|0)"
        },
        o: function() {
            return $f("Y");
        },
        Y: {
            g:1,
            c:"y = parseInt(results[{0}], 10);\n",
            s:"(\\d{4})" 
        },
        y: {
            g:1,
            c:"var ty = parseInt(results[{0}], 10);\n"
                + "y = ty > Date.y2kYear ? 1900 + ty : 2000 + ty;\n", 
            s:"(\\d{1,2})"
        },
        a: {
            g:1,
            c:"if (results[{0}] == 'am') {\n"
                + "if (!h || h == 12) { h = 0; }\n"
                + "} else { if (!h || h < 12) { h = (h || 0) + 12; }}",
            s:"(am|pm)"
        },
        A: {
            g:1,
            c:"if (results[{0}] == 'AM') {\n"
                + "if (!h || h == 12) { h = 0; }\n"
                + "} else { if (!h || h < 12) { h = (h || 0) + 12; }}",
            s:"(AM|PM)"
        },
        g: function() {
            return $f("G");
        },
        G: {
            g:1,
            c:"h = parseInt(results[{0}], 10);\n",
            s:"(\\d{1,2})" 
        },
        h: function() {
            return $f("H");
        },
        H: {
            g:1,
            c:"h = parseInt(results[{0}], 10);\n",
            s:"(\\d{2})" 
        },
        i: {
            g:1,
            c:"i = parseInt(results[{0}], 10);\n",
            s:"(\\d{2})" 
        },
        s: {
            g:1,
            c:"s = parseInt(results[{0}], 10);\n",
            s:"(\\d{2})" 
        },
        u: {
            g:1,
            c:"ms = results[{0}]; ms = parseInt(ms, 10)/Math.pow(10, ms.length - 3);\n",
            s:"(\\d+)" 
        },
        O: {
            g:1,
            c:[
                "o = results[{0}];",
                "var sn = o.substring(0,1),", 
                    "hr = o.substring(1,3)*1 + Math.floor(o.substring(3,5) / 60),", 
                    "mn = o.substring(3,5) % 60;", 
                "o = ((-12 <= (hr*60 + mn)/60) && ((hr*60 + mn)/60 <= 14))? (sn + String.leftPad(hr, 2, '0') + String.leftPad(mn, 2, '0')) : null;\n" 
            ].join("\n"),
            s: "([+\-]\\d{4})" 
        },
        P: {
            g:1,
            c:[
                "o = results[{0}];",
                "var sn = o.substring(0,1),", 
                    "hr = o.substring(1,3)*1 + Math.floor(o.substring(4,6) / 60),", 
                    "mn = o.substring(4,6) % 60;", 
                "o = ((-12 <= (hr*60 + mn)/60) && ((hr*60 + mn)/60 <= 14))? (sn + String.leftPad(hr, 2, '0') + String.leftPad(mn, 2, '0')) : null;\n" 
            ].join("\n"),
            s: "([+\-]\\d{2}:\\d{2})" 
        },
        T: {
            g:0,
            c:null,
            s:"[A-Z]{1,4}" 
        },
        Z: {
            g:1,
            c:"zz = results[{0}] * 1;\n" 
                  + "zz = (-43200 <= zz && zz <= 50400)? zz : null;\n",
            s:"([+\-]?\\d{1,5})" 
        },
        c: function() {
            var calc = [],
                arr = [
                    $f("Y", 1), 
                    $f("m", 2), 
                    $f("d", 3), 
                    $f("h", 4), 
                    $f("i", 5), 
                    $f("s", 6), 
                    {c:"ms = results[7] || '0'; ms = parseInt(ms, 10)/Math.pow(10, ms.length - 3);\n"}, 
                    {c:[ 
                        "if(results[8]) {", 
                            "if(results[8] == 'Z'){",
                                "zz = 0;", 
                            "}else if (results[8].indexOf(':') > -1){",
                                $f("P", 8).c, 
                            "}else{",
                                $f("O", 8).c, 
                            "}",
                        "}"
                    ].join('\n')}
                ];

            for (var i = 0, l = arr.length; i < l; ++i) {
                calc.push(arr[i].c);
            }

            return {
                g:1,
                c:calc.join(""),
                s:[
                    arr[0].s, 
                    "(?:", "-", arr[1].s, 
                        "(?:", "-", arr[2].s, 
                            "(?:",
                                "(?:T| )?", 
                                arr[3].s, ":", arr[4].s,  
                                "(?::", arr[5].s, ")?", 
                                "(?:(?:\\.|,)(\\d+))?", 
                                "(Z|(?:[-+]\\d{2}(?::)?\\d{2}))?", 
                            ")?",
                        ")?",
                    ")?"
                ].join("")
            }
        },
        U: {
            g:1,
            c:"u = parseInt(results[{0}], 10);\n",
            s:"(-?\\d+)" 
        }
    }
});

}());

Ext.apply(Date.prototype, {
    
    dateFormat : function(format) {
        if (Date.formatFunctions[format] == null) {
            Date.createFormat(format);
        }
        return Date.formatFunctions[format].call(this);
    },

    
    getTimezone : function() {
        
        
        
        
        
        
        
        
        
        
        
        
        return this.toString().replace(/^.* (?:\((.*)\)|([A-Z]{1,4})(?:[\-+][0-9]{4})?(?: -?\d+)?)$/, "$1$2").replace(/[^A-Z]/g, "");
    },

    
    getGMTOffset : function(colon) {
        return (this.getTimezoneOffset() > 0 ? "-" : "+")
            + String.leftPad(Math.floor(Math.abs(this.getTimezoneOffset()) / 60), 2, "0")
            + (colon ? ":" : "")
            + String.leftPad(Math.abs(this.getTimezoneOffset() % 60), 2, "0");
    },

    
    getDayOfYear: function() {
        var num = 0,
            d = this.clone(),
            m = this.getMonth(),
            i;

        for (i = 0, d.setDate(1), d.setMonth(0); i < m; d.setMonth(++i)) {
            num += d.getDaysInMonth();
        }
        return num + this.getDate() - 1;
    },

    
    getWeekOfYear : function() {
        
        var ms1d = 864e5, 
            ms7d = 7 * ms1d; 

        return function() { 
            var DC3 = Date.UTC(this.getFullYear(), this.getMonth(), this.getDate() + 3) / ms1d, 
                AWN = Math.floor(DC3 / 7), 
                Wyr = new Date(AWN * ms7d).getUTCFullYear();

            return AWN - Math.floor(Date.UTC(Wyr, 0, 7) / ms7d) + 1;
        }
    }(),

    
    isLeapYear : function() {
        var year = this.getFullYear();
        return !!((year & 3) == 0 && (year % 100 || (year % 400 == 0 && year)));
    },

    
    getFirstDayOfMonth : function() {
        var day = (this.getDay() - (this.getDate() - 1)) % 7;
        return (day < 0) ? (day + 7) : day;
    },

    
    getLastDayOfMonth : function() {
        return this.getLastDateOfMonth().getDay();
    },


    
    getFirstDateOfMonth : function() {
        return new Date(this.getFullYear(), this.getMonth(), 1);
    },

    
    getLastDateOfMonth : function() {
        return new Date(this.getFullYear(), this.getMonth(), this.getDaysInMonth());
    },

    
    getDaysInMonth: function() {
        var daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

        return function() { 
            var m = this.getMonth();

            return m == 1 && this.isLeapYear() ? 29 : daysInMonth[m];
        }
    }(),

    
    getSuffix : function() {
        switch (this.getDate()) {
            case 1:
            case 21:
            case 31:
                return "st";
            case 2:
            case 22:
                return "nd";
            case 3:
            case 23:
                return "rd";
            default:
                return "th";
        }
    },

    
    clone : function() {
        return new Date(this.getTime());
    },

    
    isDST : function() {
        
        
        return new Date(this.getFullYear(), 0, 1).getTimezoneOffset() != this.getTimezoneOffset();
    },

    
    clearTime : function(clone) {
        if (clone) {
            return this.clone().clearTime();
        }

        
        var d = this.getDate();

        
        this.setHours(0);
        this.setMinutes(0);
        this.setSeconds(0);
        this.setMilliseconds(0);

        if (this.getDate() != d) { 
            
            

            
            for (var hr = 1, c = this.add(Date.HOUR, hr); c.getDate() != d; hr++, c = this.add(Date.HOUR, hr));

            this.setDate(d);
            this.setHours(c.getHours());
        }

        return this;
    },

    
    add : function(interval, value) {
        var d = this.clone();
        if (!interval || value === 0) return d;

        switch(interval.toLowerCase()) {
            case Date.MILLI:
                d.setMilliseconds(this.getMilliseconds() + value);
                break;
            case Date.SECOND:
                d.setSeconds(this.getSeconds() + value);
                break;
            case Date.MINUTE:
                d.setMinutes(this.getMinutes() + value);
                break;
            case Date.HOUR:
                d.setHours(this.getHours() + value);
                break;
            case Date.DAY:
                d.setDate(this.getDate() + value);
                break;
            case Date.MONTH:
                var day = this.getDate();
                if (day > 28) {
                    day = Math.min(day, this.getFirstDateOfMonth().add('mo', value).getLastDateOfMonth().getDate());
                }
                d.setDate(day);
                d.setMonth(this.getMonth() + value);
                break;
            case Date.YEAR:
                d.setFullYear(this.getFullYear() + value);
                break;
        }
        return d;
    },

    
    between : function(start, end) {
        var t = this.getTime();
        return start.getTime() <= t && t <= end.getTime();
    }
});



Date.prototype.format = Date.prototype.dateFormat;



if (Ext.isSafari && (navigator.userAgent.match(/WebKit\/(\d+)/)[1] || NaN) < 420) {
    Ext.apply(Date.prototype, {
        _xMonth : Date.prototype.setMonth,
        _xDate  : Date.prototype.setDate,

        
        
        setMonth : function(num) {
            if (num <= -1) {
                var n = Math.ceil(-num),
                    back_year = Math.ceil(n / 12),
                    month = (n % 12) ? 12 - n % 12 : 0;

                this.setFullYear(this.getFullYear() - back_year);

                return this._xMonth(month);
            } else {
                return this._xMonth(num);
            }
        },

        
        
        
        setDate : function(d) {
            
            
            return this.setTime(this.getTime() - (this.getDate() - d) * 864e5);
        }
    });
}





Ext.util.MixedCollection = function(allowFunctions, keyFn){
    this.items = [];
    this.map = {};
    this.keys = [];
    this.length = 0;
    this.addEvents(
        
        'clear',
        
        'add',
        
        'replace',
        
        'remove',
        'sort'
    );
    this.allowFunctions = allowFunctions === true;
    if(keyFn){
        this.getKey = keyFn;
    }
    Ext.util.MixedCollection.superclass.constructor.call(this);
};

Ext.extend(Ext.util.MixedCollection, Ext.util.Observable, {

    
    allowFunctions : false,

    
    add : function(key, o){
        if(arguments.length == 1){
            o = arguments[0];
            key = this.getKey(o);
        }
        if(typeof key != 'undefined' && key !== null){
            var old = this.map[key];
            if(typeof old != 'undefined'){
                return this.replace(key, o);
            }
            this.map[key] = o;
        }
        this.length++;
        this.items.push(o);
        this.keys.push(key);
        this.fireEvent('add', this.length-1, o, key);
        return o;
    },

    
    getKey : function(o){
         return o.id;
    },

    
    replace : function(key, o){
        if(arguments.length == 1){
            o = arguments[0];
            key = this.getKey(o);
        }
        var old = this.map[key];
        if(typeof key == 'undefined' || key === null || typeof old == 'undefined'){
             return this.add(key, o);
        }
        var index = this.indexOfKey(key);
        this.items[index] = o;
        this.map[key] = o;
        this.fireEvent('replace', key, old, o);
        return o;
    },

    
    addAll : function(objs){
        if(arguments.length > 1 || Ext.isArray(objs)){
            var args = arguments.length > 1 ? arguments : objs;
            for(var i = 0, len = args.length; i < len; i++){
                this.add(args[i]);
            }
        }else{
            for(var key in objs){
                if(this.allowFunctions || typeof objs[key] != 'function'){
                    this.add(key, objs[key]);
                }
            }
        }
    },

    
    each : function(fn, scope){
        var items = [].concat(this.items); 
        for(var i = 0, len = items.length; i < len; i++){
            if(fn.call(scope || items[i], items[i], i, len) === false){
                break;
            }
        }
    },

    
    eachKey : function(fn, scope){
        for(var i = 0, len = this.keys.length; i < len; i++){
            fn.call(scope || window, this.keys[i], this.items[i], i, len);
        }
    },

    
    find : function(fn, scope){
        for(var i = 0, len = this.items.length; i < len; i++){
            if(fn.call(scope || window, this.items[i], this.keys[i])){
                return this.items[i];
            }
        }
        return null;
    },

    
    insert : function(index, key, o){
        if(arguments.length == 2){
            o = arguments[1];
            key = this.getKey(o);
        }
        if(this.containsKey(key)){
            this.suspendEvents();
            this.removeKey(key);
            this.resumeEvents();
        }
        if(index >= this.length){
            return this.add(key, o);
        }
        this.length++;
        this.items.splice(index, 0, o);
        if(typeof key != 'undefined' && key !== null){
            this.map[key] = o;
        }
        this.keys.splice(index, 0, key);
        this.fireEvent('add', index, o, key);
        return o;
    },

    
    remove : function(o){
        return this.removeAt(this.indexOf(o));
    },

    
    removeAt : function(index){
        if(index < this.length && index >= 0){
            this.length--;
            var o = this.items[index];
            this.items.splice(index, 1);
            var key = this.keys[index];
            if(typeof key != 'undefined'){
                delete this.map[key];
            }
            this.keys.splice(index, 1);
            this.fireEvent('remove', o, key);
            return o;
        }
        return false;
    },

    
    removeKey : function(key){
        return this.removeAt(this.indexOfKey(key));
    },

    
    getCount : function(){
        return this.length;
    },

    
    indexOf : function(o){
        return this.items.indexOf(o);
    },

    
    indexOfKey : function(key){
        return this.keys.indexOf(key);
    },

    
    item : function(key){
        var mk = this.map[key],
            item = mk !== undefined ? mk : (typeof key == 'number') ? this.items[key] : undefined;
        return typeof item != 'function' || this.allowFunctions ? item : null; 
    },

    
    itemAt : function(index){
        return this.items[index];
    },

    
    key : function(key){
        return this.map[key];
    },

    
    contains : function(o){
        return this.indexOf(o) != -1;
    },

    
    containsKey : function(key){
        return typeof this.map[key] != 'undefined';
    },

    
    clear : function(){
        this.length = 0;
        this.items = [];
        this.keys = [];
        this.map = {};
        this.fireEvent('clear');
    },

    
    first : function(){
        return this.items[0];
    },

    
    last : function(){
        return this.items[this.length-1];
    },

    
    _sort : function(property, dir, fn){
        var i, len,
            dsc   = String(dir).toUpperCase() == 'DESC' ? -1 : 1,

            
            c     = [],
            keys  = this.keys,
            items = this.items;

        
        fn = fn || function(a, b) {
            return a - b;
        };

        
        for(i = 0, len = items.length; i < len; i++){
            c[c.length] = {
                key  : keys[i],
                value: items[i],
                index: i
            };
        }

        
        c.sort(function(a, b){
            var v = fn(a[property], b[property]) * dsc;
            if(v === 0){
                v = (a.index < b.index ? -1 : 1);
            }
            return v;
        });

        
        for(i = 0, len = c.length; i < len; i++){
            items[i] = c[i].value;
            keys[i]  = c[i].key;
        }

        this.fireEvent('sort', this);
    },

    
    sort : function(dir, fn){
        this._sort('value', dir, fn);
    },

    
    reorder: function(mapping) {
        this.suspendEvents();

        var items     = this.items,
            index     = 0,
            length    = items.length,
            order     = [],
            remaining = [];

        
        for (oldIndex in mapping) {
            order[mapping[oldIndex]] = items[oldIndex];
        }

        for (index = 0; index < length; index++) {
            if (mapping[index] == undefined) {
                remaining.push(items[index]);
            }
        }

        for (index = 0; index < length; index++) {
            if (order[index] == undefined) {
                order[index] = remaining.shift();
            }
        }

        this.clear();
        this.addAll(order);

        this.resumeEvents();
        this.fireEvent('sort', this);
    },

    
    keySort : function(dir, fn){
        this._sort('key', dir, fn || function(a, b){
            var v1 = String(a).toUpperCase(), v2 = String(b).toUpperCase();
            return v1 > v2 ? 1 : (v1 < v2 ? -1 : 0);
        });
    },

    
    getRange : function(start, end){
        var items = this.items;
        if(items.length < 1){
            return [];
        }
        start = start || 0;
        end = Math.min(typeof end == 'undefined' ? this.length-1 : end, this.length-1);
        var i, r = [];
        if(start <= end){
            for(i = start; i <= end; i++) {
                r[r.length] = items[i];
            }
        }else{
            for(i = start; i >= end; i--) {
                r[r.length] = items[i];
            }
        }
        return r;
    },

    
    filter : function(property, value, anyMatch, caseSensitive){
        if(Ext.isEmpty(value, false)){
            return this.clone();
        }
        value = this.createValueMatcher(value, anyMatch, caseSensitive);
        return this.filterBy(function(o){
            return o && value.test(o[property]);
        });
    },

    
    filterBy : function(fn, scope){
        var r = new Ext.util.MixedCollection();
        r.getKey = this.getKey;
        var k = this.keys, it = this.items;
        for(var i = 0, len = it.length; i < len; i++){
            if(fn.call(scope||this, it[i], k[i])){
                r.add(k[i], it[i]);
            }
        }
        return r;
    },

    
    findIndex : function(property, value, start, anyMatch, caseSensitive){
        if(Ext.isEmpty(value, false)){
            return -1;
        }
        value = this.createValueMatcher(value, anyMatch, caseSensitive);
        return this.findIndexBy(function(o){
            return o && value.test(o[property]);
        }, null, start);
    },

    
    findIndexBy : function(fn, scope, start){
        var k = this.keys, it = this.items;
        for(var i = (start||0), len = it.length; i < len; i++){
            if(fn.call(scope||this, it[i], k[i])){
                return i;
            }
        }
        return -1;
    },

    
    createValueMatcher : function(value, anyMatch, caseSensitive, exactMatch) {
        if (!value.exec) { 
            var er = Ext.escapeRe;
            value = String(value);

            if (anyMatch === true) {
                value = er(value);
            } else {
                value = '^' + er(value);
                if (exactMatch === true) {
                    value += '$';
                }
            }
            value = new RegExp(value, caseSensitive ? '' : 'i');
         }
         return value;
    },

    
    clone : function(){
        var r = new Ext.util.MixedCollection();
        var k = this.keys, it = this.items;
        for(var i = 0, len = it.length; i < len; i++){
            r.add(k[i], it[i]);
        }
        r.getKey = this.getKey;
        return r;
    }
});

Ext.util.MixedCollection.prototype.get = Ext.util.MixedCollection.prototype.item;

Ext.util.JSON = new (function(){
    var useHasOwn = !!{}.hasOwnProperty,
        isNative = function() {
            var useNative = null;

            return function() {
                if (useNative === null) {
                    useNative = Ext.USE_NATIVE_JSON && window.JSON && JSON.toString() == '[object JSON]';
                }
        
                return useNative;
            };
        }(),
        pad = function(n) {
            return n < 10 ? "0" + n : n;
        },
        doDecode = function(json){
            return eval("(" + json + ')');    
        },
        doEncode = function(o){
            if(!Ext.isDefined(o) || o === null){
                return "null";
            }else if(Ext.isArray(o)){
                return encodeArray(o);
            }else if(Ext.isDate(o)){
                return Ext.util.JSON.encodeDate(o);
            }else if(Ext.isString(o)){
                return encodeString(o);
            }else if(typeof o == "number"){
                
                return isFinite(o) ? String(o) : "null";
            }else if(Ext.isBoolean(o)){
                return String(o);
            }else {
                var a = ["{"], b, i, v;
                for (i in o) {
                    
                    if(!o.getElementsByTagName){
                        if(!useHasOwn || o.hasOwnProperty(i)) {
                            v = o[i];
                            switch (typeof v) {
                            case "undefined":
                            case "function":
                            case "unknown":
                                break;
                            default:
                                if(b){
                                    a.push(',');
                                }
                                a.push(doEncode(i), ":",
                                        v === null ? "null" : doEncode(v));
                                b = true;
                            }
                        }
                    }
                }
                a.push("}");
                return a.join("");
            }    
        },
        m = {
            "\b": '\\b',
            "\t": '\\t',
            "\n": '\\n',
            "\f": '\\f',
            "\r": '\\r',
            '"' : '\\"',
            "\\": '\\\\'
        },
        encodeString = function(s){
            if (/["\\\x00-\x1f]/.test(s)) {
                return '"' + s.replace(/([\x00-\x1f\\"])/g, function(a, b) {
                    var c = m[b];
                    if(c){
                        return c;
                    }
                    c = b.charCodeAt();
                    return "\\u00" +
                        Math.floor(c / 16).toString(16) +
                        (c % 16).toString(16);
                }) + '"';
            }
            return '"' + s + '"';
        },
        encodeArray = function(o){
            var a = ["["], b, i, l = o.length, v;
                for (i = 0; i < l; i += 1) {
                    v = o[i];
                    switch (typeof v) {
                        case "undefined":
                        case "function":
                        case "unknown":
                            break;
                        default:
                            if (b) {
                                a.push(',');
                            }
                            a.push(v === null ? "null" : Ext.util.JSON.encode(v));
                            b = true;
                    }
                }
                a.push("]");
                return a.join("");
        };

    
    this.encodeDate = function(o){
        return '"' + o.getFullYear() + "-" +
                pad(o.getMonth() + 1) + "-" +
                pad(o.getDate()) + "T" +
                pad(o.getHours()) + ":" +
                pad(o.getMinutes()) + ":" +
                pad(o.getSeconds()) + '"';
    };

    
    this.encode = function() {
        var ec;
        return function(o) {
            if (!ec) {
                
                ec = isNative() ? JSON.stringify : doEncode;
            }
            return ec(o);
        };
    }();


    
    this.decode = function() {
        var dc;
        return function(json) {
            if (!dc) {
                
                dc = isNative() ? JSON.parse : doDecode;
            }
            return dc(json);
        };
    }();

})();

Ext.encode = Ext.util.JSON.encode;

Ext.decode = Ext.util.JSON.decode;

Ext.util.Format = function(){
    var trimRe = /^\s+|\s+$/g,
        stripTagsRE = /<\/?[^>]+>/gi,
        stripScriptsRe = /(?:<script.*?>)((\n|\r|.)*?)(?:<\/script>)/ig,
        nl2brRe = /\r?\n/g;

    return {
        
        ellipsis : function(value, len, word){
            if(value && value.length > len){
                if(word){
                    var vs = value.substr(0, len - 2),
                        index = Math.max(vs.lastIndexOf(' '), vs.lastIndexOf('.'), vs.lastIndexOf('!'), vs.lastIndexOf('?'));
                    if(index == -1 || index < (len - 15)){
                        return value.substr(0, len - 3) + "...";
                    }else{
                        return vs.substr(0, index) + "...";
                    }
                } else{
                    return value.substr(0, len - 3) + "...";
                }
            }
            return value;
        },

        
        undef : function(value){
            return value !== undefined ? value : "";
        },

        
        defaultValue : function(value, defaultValue){
            return value !== undefined && value !== '' ? value : defaultValue;
        },

        
        htmlEncode : function(value){
            return !value ? value : String(value).replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
        },

        
        htmlDecode : function(value){
            return !value ? value : String(value).replace(/&gt;/g, ">").replace(/&lt;/g, "<").replace(/&quot;/g, '"').replace(/&amp;/g, "&");
        },

        
        trim : function(value){
            return String(value).replace(trimRe, "");
        },

        
        substr : function(value, start, length){
            return String(value).substr(start, length);
        },

        
        lowercase : function(value){
            return String(value).toLowerCase();
        },

        
        uppercase : function(value){
            return String(value).toUpperCase();
        },

        
        capitalize : function(value){
            return !value ? value : value.charAt(0).toUpperCase() + value.substr(1).toLowerCase();
        },

        
        call : function(value, fn){
            if(arguments.length > 2){
                var args = Array.prototype.slice.call(arguments, 2);
                args.unshift(value);
                return eval(fn).apply(window, args);
            }else{
                return eval(fn).call(window, value);
            }
        },

        
        usMoney : function(v){
            v = (Math.round((v-0)*100))/100;
            v = (v == Math.floor(v)) ? v + ".00" : ((v*10 == Math.floor(v*10)) ? v + "0" : v);
            v = String(v);
            var ps = v.split('.'),
                whole = ps[0],
                sub = ps[1] ? '.'+ ps[1] : '.00',
                r = /(\d+)(\d{3})/;
            while (r.test(whole)) {
                whole = whole.replace(r, '$1' + ',' + '$2');
            }
            v = whole + sub;
            if(v.charAt(0) == '-'){
                return '-$' + v.substr(1);
            }
            return "$" +  v;
        },

        
        date : function(v, format){
            if(!v){
                return "";
            }
            if(!Ext.isDate(v)){
                v = new Date(Date.parse(v));
            }
            return v.dateFormat(format || "m/d/Y");
        },

        
        dateRenderer : function(format){
            return function(v){
                return Ext.util.Format.date(v, format);
            };
        },

        
        stripTags : function(v){
            return !v ? v : String(v).replace(stripTagsRE, "");
        },

        
        stripScripts : function(v){
            return !v ? v : String(v).replace(stripScriptsRe, "");
        },

        
        fileSize : function(size){
            if(size < 1024) {
                return size + " bytes";
            } else if(size < 1048576) {
                return (Math.round(((size*10) / 1024))/10) + " KB";
            } else {
                return (Math.round(((size*10) / 1048576))/10) + " MB";
            }
        },

        
        math : function(){
            var fns = {};
            return function(v, a){
                if(!fns[a]){
                    fns[a] = new Function('v', 'return v ' + a + ';');
                }
                return fns[a](v);
            }
        }(),

        
        round : function(value, precision) {
            var result = Number(value);
            if (typeof precision == 'number') {
                precision = Math.pow(10, precision);
                result = Math.round(value * precision) / precision;
            }
            return result;
        },

        
        number: function(v, format) {
            if(!format){
                return v;
            }
            v = Ext.num(v, NaN);
            if (isNaN(v)){
                return '';
            }
            var comma = ',',
                dec = '.',
                i18n = false,
                neg = v < 0;

            v = Math.abs(v);
            if(format.substr(format.length - 2) == '/i'){
                format = format.substr(0, format.length - 2);
                i18n = true;
                comma = '.';
                dec = ',';
            }

            var hasComma = format.indexOf(comma) != -1,
                psplit = (i18n ? format.replace(/[^\d\,]/g, '') : format.replace(/[^\d\.]/g, '')).split(dec);

            if(1 < psplit.length){
                v = v.toFixed(psplit[1].length);
            }else if(2 < psplit.length){
                throw ('NumberFormatException: invalid format, formats should have no more than 1 period: ' + format);
            }else{
                v = v.toFixed(0);
            }

            var fnum = v.toString();

            psplit = fnum.split('.');

            if (hasComma) {
                var cnum = psplit[0], parr = [], j = cnum.length, m = Math.floor(j / 3), n = cnum.length % 3 || 3;

                for (var i = 0; i < j; i += n) {
                    if (i != 0) {
                        n = 3;
                    }
                    parr[parr.length] = cnum.substr(i, n);
                    m -= 1;
                }
                fnum = parr.join(comma);
                if (psplit[1]) {
                    fnum += dec + psplit[1];
                }
            } else {
                if (psplit[1]) {
                    fnum = psplit[0] + dec + psplit[1];
                }
            }

            return (neg ? '-' : '') + format.replace(/[\d,?\.?]+/, fnum);
        },

        
        numberRenderer : function(format){
            return function(v){
                return Ext.util.Format.number(v, format);
            };
        },

        
        plural : function(v, s, p){
            return v +' ' + (v == 1 ? s : (p ? p : s+'s'));
        },

        
        nl2br : function(v){
            return Ext.isEmpty(v) ? '' : v.replace(nl2brRe, '<br/>');
        }
    }
}();

Ext.XTemplate = function(){
    Ext.XTemplate.superclass.constructor.apply(this, arguments);

    var me = this,
        s = me.html,
        re = /<tpl\b[^>]*>((?:(?=([^<]+))\2|<(?!tpl\b[^>]*>))*?)<\/tpl>/,
        nameRe = /^<tpl\b[^>]*?for="(.*?)"/,
        ifRe = /^<tpl\b[^>]*?if="(.*?)"/,
        execRe = /^<tpl\b[^>]*?exec="(.*?)"/,
        m,
        id = 0,
        tpls = [],
        VALUES = 'values',
        PARENT = 'parent',
        XINDEX = 'xindex',
        XCOUNT = 'xcount',
        RETURN = 'return ',
        WITHVALUES = 'with(values){ ';

    s = ['<tpl>', s, '</tpl>'].join('');

    while((m = s.match(re))){
        var m2 = m[0].match(nameRe),
            m3 = m[0].match(ifRe),
            m4 = m[0].match(execRe),
            exp = null,
            fn = null,
            exec = null,
            name = m2 && m2[1] ? m2[1] : '';

       if (m3) {
           exp = m3 && m3[1] ? m3[1] : null;
           if(exp){
               fn = new Function(VALUES, PARENT, XINDEX, XCOUNT, WITHVALUES + RETURN +(Ext.util.Format.htmlDecode(exp))+'; }');
           }
       }
       if (m4) {
           exp = m4 && m4[1] ? m4[1] : null;
           if(exp){
               exec = new Function(VALUES, PARENT, XINDEX, XCOUNT, WITHVALUES +(Ext.util.Format.htmlDecode(exp))+'; }');
           }
       }
       if(name){
           switch(name){
               case '.': name = new Function(VALUES, PARENT, WITHVALUES + RETURN + VALUES + '; }'); break;
               case '..': name = new Function(VALUES, PARENT, WITHVALUES + RETURN + PARENT + '; }'); break;
               default: name = new Function(VALUES, PARENT, WITHVALUES + RETURN + name + '; }');
           }
       }
       tpls.push({
            id: id,
            target: name,
            exec: exec,
            test: fn,
            body: m[1]||''
        });
       s = s.replace(m[0], '{xtpl'+ id + '}');
       ++id;
    }
    for(var i = tpls.length-1; i >= 0; --i){
        me.compileTpl(tpls[i]);
    }
    me.master = tpls[tpls.length-1];
    me.tpls = tpls;
};
Ext.extend(Ext.XTemplate, Ext.Template, {
    
    re : /\{([\w-\.\#]+)(?:\:([\w\.]*)(?:\((.*?)?\))?)?(\s?[\+\-\*\\]\s?[\d\.\+\-\*\\\(\)]+)?\}/g,
    
    codeRe : /\{\[((?:\\\]|.|\n)*?)\]\}/g,

    
    applySubTemplate : function(id, values, parent, xindex, xcount){
        var me = this,
            len,
            t = me.tpls[id],
            vs,
            buf = [];
        if ((t.test && !t.test.call(me, values, parent, xindex, xcount)) ||
            (t.exec && t.exec.call(me, values, parent, xindex, xcount))) {
            return '';
        }
        vs = t.target ? t.target.call(me, values, parent) : values;
        len = vs.length;
        parent = t.target ? values : parent;
        if(t.target && Ext.isArray(vs)){
            for(var i = 0, len = vs.length; i < len; i++){
                buf[buf.length] = t.compiled.call(me, vs[i], parent, i+1, len);
            }
            return buf.join('');
        }
        return t.compiled.call(me, vs, parent, xindex, xcount);
    },

    
    compileTpl : function(tpl){
        var fm = Ext.util.Format,
            useF = this.disableFormats !== true,
            sep = Ext.isGecko ? "+" : ",",
            body;

        function fn(m, name, format, args, math){
            if(name.substr(0, 4) == 'xtpl'){
                return "'"+ sep +'this.applySubTemplate('+name.substr(4)+', values, parent, xindex, xcount)'+sep+"'";
            }
            var v;
            if(name === '.'){
                v = 'values';
            }else if(name === '#'){
                v = 'xindex';
            }else if(name.indexOf('.') != -1){
                v = name;
            }else{
                v = "values['" + name + "']";
            }
            if(math){
                v = '(' + v + math + ')';
            }
            if (format && useF) {
                args = args ? ',' + args : "";
                if(format.substr(0, 5) != "this."){
                    format = "fm." + format + '(';
                }else{
                    format = 'this.call("'+ format.substr(5) + '", ';
                    args = ", values";
                }
            } else {
                args= ''; format = "("+v+" === undefined ? '' : ";
            }
            return "'"+ sep + format + v + args + ")"+sep+"'";
        }

        function codeFn(m, code){
            
            return "'" + sep + '(' + code.replace(/\\'/g, "'") + ')' + sep + "'";
        }

        
        if(Ext.isGecko){
            body = "tpl.compiled = function(values, parent, xindex, xcount){ return '" +
                   tpl.body.replace(/(\r\n|\n)/g, '\\n').replace(/'/g, "\\'").replace(this.re, fn).replace(this.codeRe, codeFn) +
                    "';};";
        }else{
            body = ["tpl.compiled = function(values, parent, xindex, xcount){ return ['"];
            body.push(tpl.body.replace(/(\r\n|\n)/g, '\\n').replace(/'/g, "\\'").replace(this.re, fn).replace(this.codeRe, codeFn));
            body.push("'].join('');};");
            body = body.join('');
        }
        eval(body);
        return this;
    },

    
    applyTemplate : function(values){
        return this.master.compiled.call(this, values, {}, 1, 1);
    },

    
    compile : function(){return this;}

    
    
    

});

Ext.XTemplate.prototype.apply = Ext.XTemplate.prototype.applyTemplate;


Ext.XTemplate.from = function(el){
    el = Ext.getDom(el);
    return new Ext.XTemplate(el.value || el.innerHTML);
};

Ext.util.CSS = function(){
	var rules = null;
   	var doc = document;

    var camelRe = /(-[a-z])/gi;
    var camelFn = function(m, a){ return a.charAt(1).toUpperCase(); };

   return {
   
   createStyleSheet : function(cssText, id){
       var ss;
       var head = doc.getElementsByTagName("head")[0];
       var rules = doc.createElement("style");
       rules.setAttribute("type", "text/css");
       if(id){
           rules.setAttribute("id", id);
       }
       if(Ext.isIE){
           head.appendChild(rules);
           ss = rules.styleSheet;
           ss.cssText = cssText;
       }else{
           try{
                rules.appendChild(doc.createTextNode(cssText));
           }catch(e){
               rules.cssText = cssText;
           }
           head.appendChild(rules);
           ss = rules.styleSheet ? rules.styleSheet : (rules.sheet || doc.styleSheets[doc.styleSheets.length-1]);
       }
       this.cacheStyleSheet(ss);
       return ss;
   },

   
   removeStyleSheet : function(id){
       var existing = doc.getElementById(id);
       if(existing){
           existing.parentNode.removeChild(existing);
       }
   },

   
   swapStyleSheet : function(id, url){
       this.removeStyleSheet(id);
       var ss = doc.createElement("link");
       ss.setAttribute("rel", "stylesheet");
       ss.setAttribute("type", "text/css");
       ss.setAttribute("id", id);
       ss.setAttribute("href", url);
       doc.getElementsByTagName("head")[0].appendChild(ss);
   },
   
   
   refreshCache : function(){
       return this.getRules(true);
   },

   
   cacheStyleSheet : function(ss){
       if(!rules){
           rules = {};
       }
       try{
           var ssRules = ss.cssRules || ss.rules;
           for(var j = ssRules.length-1; j >= 0; --j){
               rules[ssRules[j].selectorText.toLowerCase()] = ssRules[j];
           }
       }catch(e){}
   },
   
   
   getRules : function(refreshCache){
   		if(rules === null || refreshCache){
   			rules = {};
   			var ds = doc.styleSheets;
   			for(var i =0, len = ds.length; i < len; i++){
   			    try{
    		        this.cacheStyleSheet(ds[i]);
    		    }catch(e){} 
	        }
   		}
   		return rules;
   	},
   	
   	
   getRule : function(selector, refreshCache){
   		var rs = this.getRules(refreshCache);
   		if(!Ext.isArray(selector)){
   		    return rs[selector.toLowerCase()];
   		}
   		for(var i = 0; i < selector.length; i++){
			if(rs[selector[i]]){
				return rs[selector[i].toLowerCase()];
			}
		}
		return null;
   	},
   	
   	
   	
   updateRule : function(selector, property, value){
   		if(!Ext.isArray(selector)){
   			var rule = this.getRule(selector);
   			if(rule){
   				rule.style[property.replace(camelRe, camelFn)] = value;
   				return true;
   			}
   		}else{
   			for(var i = 0; i < selector.length; i++){
   				if(this.updateRule(selector[i], property, value)){
   					return true;
   				}
   			}
   		}
   		return false;
   	}
   };	
}();
Ext.util.ClickRepeater = function(el, config)
{
    this.el = Ext.get(el);
    this.el.unselectable();

    Ext.apply(this, config);

    this.addEvents(
    
        "mousedown",
    
        "click",
    
        "mouseup"
    );

    if(!this.disabled){
        this.disabled = true;
        this.enable();
    }

    
    if(this.handler){
        this.on("click", this.handler,  this.scope || this);
    }

    Ext.util.ClickRepeater.superclass.constructor.call(this);
};

Ext.extend(Ext.util.ClickRepeater, Ext.util.Observable, {
    interval : 20,
    delay: 250,
    preventDefault : true,
    stopDefault : false,
    timer : 0,

    
    enable: function(){
        if(this.disabled){
            this.el.on('mousedown', this.handleMouseDown, this);
            if (Ext.isIE){
                this.el.on('dblclick', this.handleDblClick, this);
            }
            if(this.preventDefault || this.stopDefault){
                this.el.on('click', this.eventOptions, this);
            }
        }
        this.disabled = false;
    },

    
    disable: function( force){
        if(force || !this.disabled){
            clearTimeout(this.timer);
            if(this.pressClass){
                this.el.removeClass(this.pressClass);
            }
            Ext.getDoc().un('mouseup', this.handleMouseUp, this);
            this.el.removeAllListeners();
        }
        this.disabled = true;
    },

    
    setDisabled: function(disabled){
        this[disabled ? 'disable' : 'enable']();
    },

    eventOptions: function(e){
        if(this.preventDefault){
            e.preventDefault();
        }
        if(this.stopDefault){
            e.stopEvent();
        }
    },

    
    destroy : function() {
        this.disable(true);
        Ext.destroy(this.el);
        this.purgeListeners();
    },

    handleDblClick : function(){
        clearTimeout(this.timer);
        this.el.blur();

        this.fireEvent("mousedown", this);
        this.fireEvent("click", this);
    },

    
    handleMouseDown : function(){
        clearTimeout(this.timer);
        this.el.blur();
        if(this.pressClass){
            this.el.addClass(this.pressClass);
        }
        this.mousedownTime = new Date();

        Ext.getDoc().on("mouseup", this.handleMouseUp, this);
        this.el.on("mouseout", this.handleMouseOut, this);

        this.fireEvent("mousedown", this);
        this.fireEvent("click", this);

        
        if (this.accelerate) {
            this.delay = 400;
        }
        this.timer = this.click.defer(this.delay || this.interval, this);
    },

    
    click : function(){
        this.fireEvent("click", this);
        this.timer = this.click.defer(this.accelerate ?
            this.easeOutExpo(this.mousedownTime.getElapsed(),
                400,
                -390,
                12000) :
            this.interval, this);
    },

    easeOutExpo : function (t, b, c, d) {
        return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
    },

    
    handleMouseOut : function(){
        clearTimeout(this.timer);
        if(this.pressClass){
            this.el.removeClass(this.pressClass);
        }
        this.el.on("mouseover", this.handleMouseReturn, this);
    },

    
    handleMouseReturn : function(){
        this.el.un("mouseover", this.handleMouseReturn, this);
        if(this.pressClass){
            this.el.addClass(this.pressClass);
        }
        this.click();
    },

    
    handleMouseUp : function(){
        clearTimeout(this.timer);
        this.el.un("mouseover", this.handleMouseReturn, this);
        this.el.un("mouseout", this.handleMouseOut, this);
        Ext.getDoc().un("mouseup", this.handleMouseUp, this);
        this.el.removeClass(this.pressClass);
        this.fireEvent("mouseup", this);
    }
});
Ext.KeyNav = function(el, config){
    this.el = Ext.get(el);
    Ext.apply(this, config);
    if(!this.disabled){
        this.disabled = true;
        this.enable();
    }
};

Ext.KeyNav.prototype = {
    
    disabled : false,
    
    defaultEventAction: "stopEvent",
    
    forceKeyDown : false,

    
    relay : function(e){
        var k = e.getKey();
        var h = this.keyToHandler[k];
        if(h && this[h]){
            if(this.doRelay(e, this[h], h) !== true){
                e[this.defaultEventAction]();
            }
        }
    },

    
    doRelay : function(e, h, hname){
        return h.call(this.scope || this, e);
    },

    
    enter : false,
    left : false,
    right : false,
    up : false,
    down : false,
    tab : false,
    esc : false,
    pageUp : false,
    pageDown : false,
    del : false,
    home : false,
    end : false,

    
    keyToHandler : {
        37 : "left",
        39 : "right",
        38 : "up",
        40 : "down",
        33 : "pageUp",
        34 : "pageDown",
        46 : "del",
        36 : "home",
        35 : "end",
        13 : "enter",
        27 : "esc",
        9  : "tab"
    },
    
    stopKeyUp: function(e) {
        var k = e.getKey();

        if (k >= 37 && k <= 40) {
            
            
            e.stopEvent();
        }
    },
    
    
    destroy: function(){
        this.disable();    
    },

	
	enable: function() {
        if (this.disabled) {
            if (Ext.isSafari2) {
                
                this.el.on('keyup', this.stopKeyUp, this);
            }

            this.el.on(this.isKeydown()? 'keydown' : 'keypress', this.relay, this);
            this.disabled = false;
        }
    },

	
	disable: function() {
        if (!this.disabled) {
            if (Ext.isSafari2) {
                
                this.el.un('keyup', this.stopKeyUp, this);
            }

            this.el.un(this.isKeydown()? 'keydown' : 'keypress', this.relay, this);
            this.disabled = true;
        }
    },
    
    
    setDisabled : function(disabled){
        this[disabled ? "disable" : "enable"]();
    },
    
    
    isKeydown: function(){
        return this.forceKeyDown || Ext.EventManager.useKeydown;
    }
};

Ext.KeyMap = function(el, config, eventName){
    this.el  = Ext.get(el);
    this.eventName = eventName || "keydown";
    this.bindings = [];
    if(config){
        this.addBinding(config);
    }
    this.enable();
};

Ext.KeyMap.prototype = {
    
    stopEvent : false,

    
	addBinding : function(config){
        if(Ext.isArray(config)){
            Ext.each(config, function(c){
                this.addBinding(c);
            }, this);
            return;
        }
        var keyCode = config.key,
            fn = config.fn || config.handler,
            scope = config.scope;

	if (config.stopEvent) {
	    this.stopEvent = config.stopEvent;    
	}	

        if(typeof keyCode == "string"){
            var ks = [];
            var keyString = keyCode.toUpperCase();
            for(var j = 0, len = keyString.length; j < len; j++){
                ks.push(keyString.charCodeAt(j));
            }
            keyCode = ks;
        }
        var keyArray = Ext.isArray(keyCode);
        
        var handler = function(e){
            if(this.checkModifiers(config, e)){
                var k = e.getKey();
                if(keyArray){
                    for(var i = 0, len = keyCode.length; i < len; i++){
                        if(keyCode[i] == k){
                          if(this.stopEvent){
                              e.stopEvent();
                          }
                          fn.call(scope || window, k, e);
                          return;
                        }
                    }
                }else{
                    if(k == keyCode){
                        if(this.stopEvent){
                           e.stopEvent();
                        }
                        fn.call(scope || window, k, e);
                    }
                }
            }
        };
        this.bindings.push(handler);
	},
    
    
    checkModifiers: function(config, e){
        var val, key, keys = ['shift', 'ctrl', 'alt'];
        for (var i = 0, len = keys.length; i < len; ++i){
            key = keys[i];
            val = config[key];
            if(!(val === undefined || (val === e[key + 'Key']))){
                return false;
            }
        }
        return true;
    },

    
    on : function(key, fn, scope){
        var keyCode, shift, ctrl, alt;
        if(typeof key == "object" && !Ext.isArray(key)){
            keyCode = key.key;
            shift = key.shift;
            ctrl = key.ctrl;
            alt = key.alt;
        }else{
            keyCode = key;
        }
        this.addBinding({
            key: keyCode,
            shift: shift,
            ctrl: ctrl,
            alt: alt,
            fn: fn,
            scope: scope
        });
    },

    
    handleKeyDown : function(e){
	    if(this.enabled){ 
    	    var b = this.bindings;
    	    for(var i = 0, len = b.length; i < len; i++){
    	        b[i].call(this, e);
    	    }
	    }
	},

	
	isEnabled : function(){
	    return this.enabled;
	},

	
	enable: function(){
		if(!this.enabled){
		    this.el.on(this.eventName, this.handleKeyDown, this);
		    this.enabled = true;
		}
	},

	
	disable: function(){
		if(this.enabled){
		    this.el.removeListener(this.eventName, this.handleKeyDown, this);
		    this.enabled = false;
		}
	},
    
    
    setDisabled : function(disabled){
        this[disabled ? "disable" : "enable"]();
    }
};
Ext.util.TextMetrics = function(){
    var shared;
    return {
        
        measure : function(el, text, fixedWidth){
            if(!shared){
                shared = Ext.util.TextMetrics.Instance(el, fixedWidth);
            }
            shared.bind(el);
            shared.setFixedWidth(fixedWidth || 'auto');
            return shared.getSize(text);
        },

        
        createInstance : function(el, fixedWidth){
            return Ext.util.TextMetrics.Instance(el, fixedWidth);
        }
    };
}();

Ext.util.TextMetrics.Instance = function(bindTo, fixedWidth){
    var ml = new Ext.Element(document.createElement('div'));
    document.body.appendChild(ml.dom);
    ml.position('absolute');
    ml.setLeftTop(-1000, -1000);
    ml.hide();

    if(fixedWidth){
        ml.setWidth(fixedWidth);
    }

    var instance = {
        
        getSize : function(text){
            ml.update(text);
            var s = ml.getSize();
            ml.update('');
            return s;
        },

        
        bind : function(el){
            ml.setStyle(
                Ext.fly(el).getStyles('font-size','font-style', 'font-weight', 'font-family','line-height', 'text-transform', 'letter-spacing')
            );
        },

        
        setFixedWidth : function(width){
            ml.setWidth(width);
        },

        
        getWidth : function(text){
            ml.dom.style.width = 'auto';
            return this.getSize(text).width;
        },

        
        getHeight : function(text){
            return this.getSize(text).height;
        }
    };

    instance.bind(bindTo);

    return instance;
};

Ext.Element.addMethods({
    
    getTextWidth : function(text, min, max){
        return (Ext.util.TextMetrics.measure(this.dom, Ext.value(text, this.dom.innerHTML, true)).width).constrain(min || 0, max || 1000000);
    }
});

Ext.util.Cookies = {
    
    set : function(name, value){
        var argv = arguments;
        var argc = arguments.length;
        var expires = (argc > 2) ? argv[2] : null;
        var path = (argc > 3) ? argv[3] : '/';
        var domain = (argc > 4) ? argv[4] : null;
        var secure = (argc > 5) ? argv[5] : false;
        document.cookie = name + "=" + escape(value) + ((expires === null) ? "" : ("; expires=" + expires.toGMTString())) + ((path === null) ? "" : ("; path=" + path)) + ((domain === null) ? "" : ("; domain=" + domain)) + ((secure === true) ? "; secure" : "");
    },

    
    get : function(name){
        var arg = name + "=";
        var alen = arg.length;
        var clen = document.cookie.length;
        var i = 0;
        var j = 0;
        while(i < clen){
            j = i + alen;
            if(document.cookie.substring(i, j) == arg){
                return Ext.util.Cookies.getCookieVal(j);
            }
            i = document.cookie.indexOf(" ", i) + 1;
            if(i === 0){
                break;
            }
        }
        return null;
    },

    
    clear : function(name){
        if(Ext.util.Cookies.get(name)){
            document.cookie = name + "=" + "; expires=Thu, 01-Jan-70 00:00:01 GMT";
        }
    },
    
    getCookieVal : function(offset){
        var endstr = document.cookie.indexOf(";", offset);
        if(endstr == -1){
            endstr = document.cookie.length;
        }
        return unescape(document.cookie.substring(offset, endstr));
    }
};
Ext.handleError = function(e) {
    throw e;
};


Ext.Error = function(message) {
    
    this.message = (this.lang[message]) ? this.lang[message] : message;
};

Ext.Error.prototype = new Error();
Ext.apply(Ext.Error.prototype, {
    
    lang: {},

    name: 'Ext.Error',
    
    getName : function() {
        return this.name;
    },
    
    getMessage : function() {
        return this.message;
    },
    
    toJson : function() {
        return Ext.encode(this);
    }
});

Ext.ComponentMgr = function(){
    var all = new Ext.util.MixedCollection();
    var types = {};
    var ptypes = {};

    return {
        
        register : function(c){
            all.add(c);
        },

        
        unregister : function(c){
            all.remove(c);
        },

        
        get : function(id){
            return all.get(id);
        },

        
        onAvailable : function(id, fn, scope){
            all.on("add", function(index, o){
                if(o.id == id){
                    fn.call(scope || o, o);
                    all.un("add", fn, scope);
                }
            });
        },

        
        all : all,
        
        
        types : types,
        
        
        ptypes: ptypes,
        
        
        isRegistered : function(xtype){
            return types[xtype] !== undefined;    
        },
        
        
        isPluginRegistered : function(ptype){
            return ptypes[ptype] !== undefined;    
        },        

        
        registerType : function(xtype, cls){
            types[xtype] = cls;
            cls.xtype = xtype;
        },

        
        create : function(config, defaultType){
            return config.render ? config : new types[config.xtype || defaultType](config);
        },

        
        registerPlugin : function(ptype, cls){
            ptypes[ptype] = cls;
            cls.ptype = ptype;
        },

        
        createPlugin : function(config, defaultType){
            var PluginCls = ptypes[config.ptype || defaultType];
            if (PluginCls.init) {
                return PluginCls;                
            } else {
                return new PluginCls(config);
            }            
        }
    };
}();


Ext.reg = Ext.ComponentMgr.registerType; 

Ext.preg = Ext.ComponentMgr.registerPlugin;

Ext.create = Ext.ComponentMgr.create;
Ext.Component = function(config){
    config = config || {};
    if(config.initialConfig){
        if(config.isAction){           
            this.baseAction = config;
        }
        config = config.initialConfig; 
    }else if(config.tagName || config.dom || Ext.isString(config)){ 
        config = {applyTo: config, id: config.id || config};
    }

    
    this.initialConfig = config;

    Ext.apply(this, config);
    this.addEvents(
        
        'added',
        
        'disable',
        
        'enable',
        
        'beforeshow',
        
        'show',
        
        'beforehide',
        
        'hide',
        
        'removed',
        
        'beforerender',
        
        'render',
        
        'afterrender',
        
        'beforedestroy',
        
        'destroy',
        
        'beforestaterestore',
        
        'staterestore',
        
        'beforestatesave',
        
        'statesave'
    );
    this.getId();
    Ext.ComponentMgr.register(this);
    Ext.Component.superclass.constructor.call(this);

    if(this.baseAction){
        this.baseAction.addComponent(this);
    }

    this.initComponent();

    if(this.plugins){
        if(Ext.isArray(this.plugins)){
            for(var i = 0, len = this.plugins.length; i < len; i++){
                this.plugins[i] = this.initPlugin(this.plugins[i]);
            }
        }else{
            this.plugins = this.initPlugin(this.plugins);
        }
    }

    if(this.stateful !== false){
        this.initState();
    }

    if(this.applyTo){
        this.applyToMarkup(this.applyTo);
        delete this.applyTo;
    }else if(this.renderTo){
        this.render(this.renderTo);
        delete this.renderTo;
    }
};


Ext.Component.AUTO_ID = 1000;

Ext.extend(Ext.Component, Ext.util.Observable, {
    
    
    
    
    
    
    

    
    
    
    
    
    
    
    
    
    disabled : false,
    
    hidden : false,
    
    
    
    
    
    
    
    autoEl : 'div',

    
    disabledClass : 'x-item-disabled',
    
    allowDomMove : true,
    
    autoShow : false,
    
    hideMode : 'display',
    
    hideParent : false,
    
    
    
    
    
    rendered : false,

    
    

    

    
    tplWriteMode : 'overwrite',

    
    
    
    bubbleEvents: [],


    
    ctype : 'Ext.Component',

    
    actionMode : 'el',

    
    getActionEl : function(){
        return this[this.actionMode];
    },

    initPlugin : function(p){
        if(p.ptype && !Ext.isFunction(p.init)){
            p = Ext.ComponentMgr.createPlugin(p);
        }else if(Ext.isString(p)){
            p = Ext.ComponentMgr.createPlugin({
                ptype: p
            });
        }
        p.init(this);
        return p;
    },

    
    initComponent : function(){
        
        if(this.listeners){
            this.on(this.listeners);
            delete this.listeners;
        }
        this.enableBubble(this.bubbleEvents);
    },

    
    render : function(container, position){
        if(!this.rendered && this.fireEvent('beforerender', this) !== false){
            if(!container && this.el){
                this.el = Ext.get(this.el);
                container = this.el.dom.parentNode;
                this.allowDomMove = false;
            }
            this.container = Ext.get(container);
            if(this.ctCls){
                this.container.addClass(this.ctCls);
            }
            this.rendered = true;
            if(position !== undefined){
                if(Ext.isNumber(position)){
                    position = this.container.dom.childNodes[position];
                }else{
                    position = Ext.getDom(position);
                }
            }
            this.onRender(this.container, position || null);
            if(this.autoShow){
                this.el.removeClass(['x-hidden','x-hide-' + this.hideMode]);
            }
            if(this.cls){
                this.el.addClass(this.cls);
                delete this.cls;
            }
            if(this.style){
                this.el.applyStyles(this.style);
                delete this.style;
            }
            if(this.overCls){
                this.el.addClassOnOver(this.overCls);
            }
            this.fireEvent('render', this);


            
            
            var contentTarget = this.getContentTarget();
            if (this.html){
                contentTarget.update(Ext.DomHelper.markup(this.html));
                delete this.html;
            }
            if (this.contentEl){
                var ce = Ext.getDom(this.contentEl);
                Ext.fly(ce).removeClass(['x-hidden', 'x-hide-display']);
                contentTarget.appendChild(ce);
            }
            if (this.tpl) {
                if (!this.tpl.compile) {
                    this.tpl = new Ext.XTemplate(this.tpl);
                }
                if (this.data) {
                    this.tpl[this.tplWriteMode](contentTarget, this.data);
                    delete this.data;
                }
            }
            this.afterRender(this.container);


            if(this.hidden){
                
                this.doHide();
            }
            if(this.disabled){
                
                this.disable(true);
            }

            if(this.stateful !== false){
                this.initStateEvents();
            }
            this.fireEvent('afterrender', this);
        }
        return this;
    },


    
    update: function(htmlOrData, loadScripts, cb) {
        var contentTarget = this.getContentTarget();
        if (this.tpl && typeof htmlOrData !== "string") {
            this.tpl[this.tplWriteMode](contentTarget, htmlOrData || {});
        } else {
            var html = Ext.isObject(htmlOrData) ? Ext.DomHelper.markup(htmlOrData) : htmlOrData;
            contentTarget.update(html, loadScripts, cb);
        }
    },


    
    onAdded : function(container, pos) {
        this.ownerCt = container;
        this.initRef();
        this.fireEvent('added', this, container, pos);
    },

    
    onRemoved : function() {
        this.removeRef();
        this.fireEvent('removed', this, this.ownerCt);
        delete this.ownerCt;
    },

    
    initRef : function() {
        
        if(this.ref && !this.refOwner){
            var levels = this.ref.split('/'),
                last = levels.length,
                i = 0,
                t = this;

            while(t && i < last){
                t = t.ownerCt;
                ++i;
            }
            if(t){
                t[this.refName = levels[--i]] = this;
                
                this.refOwner = t;
            }
        }
    },

    removeRef : function() {
        if (this.refOwner && this.refName) {
            delete this.refOwner[this.refName];
            delete this.refOwner;
        }
    },

    
    initState : function(){
        if(Ext.state.Manager){
            var id = this.getStateId();
            if(id){
                var state = Ext.state.Manager.get(id);
                if(state){
                    if(this.fireEvent('beforestaterestore', this, state) !== false){
                        this.applyState(Ext.apply({}, state));
                        this.fireEvent('staterestore', this, state);
                    }
                }
            }
        }
    },

    
    getStateId : function(){
        return this.stateId || ((/^(ext-comp-|ext-gen)/).test(String(this.id)) ? null : this.id);
    },

    
    initStateEvents : function(){
        if(this.stateEvents){
            for(var i = 0, e; e = this.stateEvents[i]; i++){
                this.on(e, this.saveState, this, {delay:100});
            }
        }
    },

    
    applyState : function(state){
        if(state){
            Ext.apply(this, state);
        }
    },

    
    getState : function(){
        return null;
    },

    
    saveState : function(){
        if(Ext.state.Manager && this.stateful !== false){
            var id = this.getStateId();
            if(id){
                var state = this.getState();
                if(this.fireEvent('beforestatesave', this, state) !== false){
                    Ext.state.Manager.set(id, state);
                    this.fireEvent('statesave', this, state);
                }
            }
        }
    },

    
    applyToMarkup : function(el){
        this.allowDomMove = false;
        this.el = Ext.get(el);
        this.render(this.el.dom.parentNode);
    },

    
    addClass : function(cls){
        if(this.el){
            this.el.addClass(cls);
        }else{
            this.cls = this.cls ? this.cls + ' ' + cls : cls;
        }
        return this;
    },

    
    removeClass : function(cls){
        if(this.el){
            this.el.removeClass(cls);
        }else if(this.cls){
            this.cls = this.cls.split(' ').remove(cls).join(' ');
        }
        return this;
    },

    
    
    onRender : function(ct, position){
        if(!this.el && this.autoEl){
            if(Ext.isString(this.autoEl)){
                this.el = document.createElement(this.autoEl);
            }else{
                var div = document.createElement('div');
                Ext.DomHelper.overwrite(div, this.autoEl);
                this.el = div.firstChild;
            }
            if (!this.el.id) {
                this.el.id = this.getId();
            }
        }
        if(this.el){
            this.el = Ext.get(this.el);
            if(this.allowDomMove !== false){
                ct.dom.insertBefore(this.el.dom, position);
                if (div) {
                    Ext.removeNode(div);
                    div = null;
                }
            }
        }
    },

    
    getAutoCreate : function(){
        var cfg = Ext.isObject(this.autoCreate) ?
                      this.autoCreate : Ext.apply({}, this.defaultAutoCreate);
        if(this.id && !cfg.id){
            cfg.id = this.id;
        }
        return cfg;
    },

    
    afterRender : Ext.emptyFn,

    
    destroy : function(){
        if(!this.isDestroyed){
            if(this.fireEvent('beforedestroy', this) !== false){
                this.destroying = true;
                this.beforeDestroy();
                if(this.ownerCt && this.ownerCt.remove){
                    this.ownerCt.remove(this, false);
                }
                if(this.rendered){
                    this.el.remove();
                    if(this.actionMode == 'container' || this.removeMode == 'container'){
                        this.container.remove();
                    }
                }
                
                if(this.focusTask && this.focusTask.cancel){
                    this.focusTask.cancel();
                }
                this.onDestroy();
                Ext.ComponentMgr.unregister(this);
                this.fireEvent('destroy', this);
                this.purgeListeners();
                this.destroying = false;
                this.isDestroyed = true;
            }
        }
    },

    deleteMembers : function(){
        var args = arguments;
        for(var i = 0, len = args.length; i < len; ++i){
            delete this[args[i]];
        }
    },

    
    beforeDestroy : Ext.emptyFn,

    
    onDestroy  : Ext.emptyFn,

    
    getEl : function(){
        return this.el;
    },

    
    getContentTarget : function(){
        return this.el;
    },

    
    getId : function(){
        return this.id || (this.id = 'ext-comp-' + (++Ext.Component.AUTO_ID));
    },

    
    getItemId : function(){
        return this.itemId || this.getId();
    },

    
    focus : function(selectText, delay){
        if(delay){
            this.focusTask = new Ext.util.DelayedTask(this.focus, this, [selectText, false]);
            this.focusTask.delay(Ext.isNumber(delay) ? delay : 10);
            return;
        }
        if(this.rendered && !this.isDestroyed){
            this.el.focus();
            if(selectText === true){
                this.el.dom.select();
            }
        }
        return this;
    },

    
    blur : function(){
        if(this.rendered){
            this.el.blur();
        }
        return this;
    },

    
    disable : function( silent){
        if(this.rendered){
            this.onDisable();
        }
        this.disabled = true;
        if(silent !== true){
            this.fireEvent('disable', this);
        }
        return this;
    },

    
    onDisable : function(){
        this.getActionEl().addClass(this.disabledClass);
        this.el.dom.disabled = true;
    },

    
    enable : function(){
        if(this.rendered){
            this.onEnable();
        }
        this.disabled = false;
        this.fireEvent('enable', this);
        return this;
    },

    
    onEnable : function(){
        this.getActionEl().removeClass(this.disabledClass);
        this.el.dom.disabled = false;
    },

    
    setDisabled : function(disabled){
        return this[disabled ? 'disable' : 'enable']();
    },

    
    show : function(){
        if(this.fireEvent('beforeshow', this) !== false){
            this.hidden = false;
            if(this.autoRender){
                this.render(Ext.isBoolean(this.autoRender) ? Ext.getBody() : this.autoRender);
            }
            if(this.rendered){
                this.onShow();
            }
            this.fireEvent('show', this);
        }
        return this;
    },

    
    onShow : function(){
        this.getVisibilityEl().removeClass('x-hide-' + this.hideMode);
    },

    
    hide : function(){
        if(this.fireEvent('beforehide', this) !== false){
            this.doHide();
            this.fireEvent('hide', this);
        }
        return this;
    },

    
    doHide: function(){
        this.hidden = true;
        if(this.rendered){
            this.onHide();
        }
    },

    
    onHide : function(){
        this.getVisibilityEl().addClass('x-hide-' + this.hideMode);
    },

    
    getVisibilityEl : function(){
        return this.hideParent ? this.container : this.getActionEl();
    },

    
    setVisible : function(visible){
        return this[visible ? 'show' : 'hide']();
    },

    
    isVisible : function(){
        return this.rendered && this.getVisibilityEl().isVisible();
    },

    
    cloneConfig : function(overrides){
        overrides = overrides || {};
        var id = overrides.id || Ext.id();
        var cfg = Ext.applyIf(overrides, this.initialConfig);
        cfg.id = id; 
        return new this.constructor(cfg);
    },

    
    getXType : function(){
        return this.constructor.xtype;
    },

    
    isXType : function(xtype, shallow){
        
        if (Ext.isFunction(xtype)){
            xtype = xtype.xtype; 
        }else if (Ext.isObject(xtype)){
            xtype = xtype.constructor.xtype; 
        }

        return !shallow ? ('/' + this.getXTypes() + '/').indexOf('/' + xtype + '/') != -1 : this.constructor.xtype == xtype;
    },

    
    getXTypes : function(){
        var tc = this.constructor;
        if(!tc.xtypes){
            var c = [], sc = this;
            while(sc && sc.constructor.xtype){
                c.unshift(sc.constructor.xtype);
                sc = sc.constructor.superclass;
            }
            tc.xtypeChain = c;
            tc.xtypes = c.join('/');
        }
        return tc.xtypes;
    },

    
    findParentBy : function(fn) {
        for (var p = this.ownerCt; (p != null) && !fn(p, this); p = p.ownerCt);
        return p || null;
    },

    
    findParentByType : function(xtype) {
        return Ext.isFunction(xtype) ?
            this.findParentBy(function(p){
                return p.constructor === xtype;
            }) :
            this.findParentBy(function(p){
                return p.constructor.xtype === xtype;
            });
    },

    
    getPositionEl : function(){
        return this.positionEl || this.el;
    },

    
    purgeListeners : function(){
        Ext.Component.superclass.purgeListeners.call(this);
        if(this.mons){
            this.on('beforedestroy', this.clearMons, this, {single: true});
        }
    },

    
    clearMons : function(){
        Ext.each(this.mons, function(m){
            m.item.un(m.ename, m.fn, m.scope);
        }, this);
        this.mons = [];
    },

    
    createMons: function(){
        if(!this.mons){
            this.mons = [];
            this.on('beforedestroy', this.clearMons, this, {single: true});
        }
    },

    
    mon : function(item, ename, fn, scope, opt){
        this.createMons();
        if(Ext.isObject(ename)){
            var propRe = /^(?:scope|delay|buffer|single|stopEvent|preventDefault|stopPropagation|normalized|args|delegate)$/;

            var o = ename;
            for(var e in o){
                if(propRe.test(e)){
                    continue;
                }
                if(Ext.isFunction(o[e])){
                    
                    this.mons.push({
                        item: item, ename: e, fn: o[e], scope: o.scope
                    });
                    item.on(e, o[e], o.scope, o);
                }else{
                    
                    this.mons.push({
                        item: item, ename: e, fn: o[e], scope: o.scope
                    });
                    item.on(e, o[e]);
                }
            }
            return;
        }

        this.mons.push({
            item: item, ename: ename, fn: fn, scope: scope
        });
        item.on(ename, fn, scope, opt);
    },

    
    mun : function(item, ename, fn, scope){
        var found, mon;
        this.createMons();
        for(var i = 0, len = this.mons.length; i < len; ++i){
            mon = this.mons[i];
            if(item === mon.item && ename == mon.ename && fn === mon.fn && scope === mon.scope){
                this.mons.splice(i, 1);
                item.un(ename, fn, scope);
                found = true;
                break;
            }
        }
        return found;
    },

    
    nextSibling : function(){
        if(this.ownerCt){
            var index = this.ownerCt.items.indexOf(this);
            if(index != -1 && index+1 < this.ownerCt.items.getCount()){
                return this.ownerCt.items.itemAt(index+1);
            }
        }
        return null;
    },

    
    previousSibling : function(){
        if(this.ownerCt){
            var index = this.ownerCt.items.indexOf(this);
            if(index > 0){
                return this.ownerCt.items.itemAt(index-1);
            }
        }
        return null;
    },

    
    getBubbleTarget : function(){
        return this.ownerCt;
    }
});

Ext.reg('component', Ext.Component);
Ext.Action = Ext.extend(Object, {
    
    
    
    
    
    
    

    constructor : function(config){
        this.initialConfig = config;
        this.itemId = config.itemId = (config.itemId || config.id || Ext.id());
        this.items = [];
    },
    
    
    isAction : true,

    
    setText : function(text){
        this.initialConfig.text = text;
        this.callEach('setText', [text]);
    },

    
    getText : function(){
        return this.initialConfig.text;
    },

    
    setIconClass : function(cls){
        this.initialConfig.iconCls = cls;
        this.callEach('setIconClass', [cls]);
    },

    
    getIconClass : function(){
        return this.initialConfig.iconCls;
    },

    
    setDisabled : function(v){
        this.initialConfig.disabled = v;
        this.callEach('setDisabled', [v]);
    },

    
    enable : function(){
        this.setDisabled(false);
    },

    
    disable : function(){
        this.setDisabled(true);
    },

    
    isDisabled : function(){
        return this.initialConfig.disabled;
    },

    
    setHidden : function(v){
        this.initialConfig.hidden = v;
        this.callEach('setVisible', [!v]);
    },

    
    show : function(){
        this.setHidden(false);
    },

    
    hide : function(){
        this.setHidden(true);
    },

    
    isHidden : function(){
        return this.initialConfig.hidden;
    },

    
    setHandler : function(fn, scope){
        this.initialConfig.handler = fn;
        this.initialConfig.scope = scope;
        this.callEach('setHandler', [fn, scope]);
    },

    
    each : function(fn, scope){
        Ext.each(this.items, fn, scope);
    },

    
    callEach : function(fnName, args){
        var cs = this.items;
        for(var i = 0, len = cs.length; i < len; i++){
            cs[i][fnName].apply(cs[i], args);
        }
    },

    
    addComponent : function(comp){
        this.items.push(comp);
        comp.on('destroy', this.removeComponent, this);
    },

    
    removeComponent : function(comp){
        this.items.remove(comp);
    },

    
    execute : function(){
        this.initialConfig.handler.apply(this.initialConfig.scope || window, arguments);
    }
});

(function(){
Ext.Layer = function(config, existingEl){
    config = config || {};
    var dh = Ext.DomHelper;
    var cp = config.parentEl, pel = cp ? Ext.getDom(cp) : document.body;
    if(existingEl){
        this.dom = Ext.getDom(existingEl);
    }
    if(!this.dom){
        var o = config.dh || {tag: 'div', cls: 'x-layer'};
        this.dom = dh.append(pel, o);
    }
    if(config.cls){
        this.addClass(config.cls);
    }
    this.constrain = config.constrain !== false;
    this.setVisibilityMode(Ext.Element.VISIBILITY);
    if(config.id){
        this.id = this.dom.id = config.id;
    }else{
        this.id = Ext.id(this.dom);
    }
    this.zindex = config.zindex || this.getZIndex();
    this.position('absolute', this.zindex);
    if(config.shadow){
        this.shadowOffset = config.shadowOffset || 4;
        this.shadow = new Ext.Shadow({
            offset : this.shadowOffset,
            mode : config.shadow
        });
    }else{
        this.shadowOffset = 0;
    }
    this.useShim = config.shim !== false && Ext.useShims;
    this.useDisplay = config.useDisplay;
    this.hide();
};

var supr = Ext.Element.prototype;


var shims = [];

Ext.extend(Ext.Layer, Ext.Element, {

    getZIndex : function(){
        return this.zindex || parseInt((this.getShim() || this).getStyle('z-index'), 10) || 11000;
    },

    getShim : function(){
        if(!this.useShim){
            return null;
        }
        if(this.shim){
            return this.shim;
        }
        var shim = shims.shift();
        if(!shim){
            shim = this.createShim();
            shim.enableDisplayMode('block');
            shim.dom.style.display = 'none';
            shim.dom.style.visibility = 'visible';
        }
        var pn = this.dom.parentNode;
        if(shim.dom.parentNode != pn){
            pn.insertBefore(shim.dom, this.dom);
        }
        shim.setStyle('z-index', this.getZIndex()-2);
        this.shim = shim;
        return shim;
    },

    hideShim : function(){
        if(this.shim){
            this.shim.setDisplayed(false);
            shims.push(this.shim);
            delete this.shim;
        }
    },

    disableShadow : function(){
        if(this.shadow){
            this.shadowDisabled = true;
            this.shadow.hide();
            this.lastShadowOffset = this.shadowOffset;
            this.shadowOffset = 0;
        }
    },

    enableShadow : function(show){
        if(this.shadow){
            this.shadowDisabled = false;
            this.shadowOffset = this.lastShadowOffset;
            delete this.lastShadowOffset;
            if(show){
                this.sync(true);
            }
        }
    },

    
    
    
    sync : function(doShow){
        var shadow = this.shadow;
        if(!this.updating && this.isVisible() && (shadow || this.useShim)){
            var shim = this.getShim(),
                w = this.getWidth(),
                h = this.getHeight(),
                l = this.getLeft(true),
                t = this.getTop(true);

            if(shadow && !this.shadowDisabled){
                if(doShow && !shadow.isVisible()){
                    shadow.show(this);
                }else{
                    shadow.realign(l, t, w, h);
                }
                if(shim){
                    if(doShow){
                       shim.show();
                    }
                    
                    var shadowAdj = shadow.el.getXY(), shimStyle = shim.dom.style,
                        shadowSize = shadow.el.getSize();
                    shimStyle.left = (shadowAdj[0])+'px';
                    shimStyle.top = (shadowAdj[1])+'px';
                    shimStyle.width = (shadowSize.width)+'px';
                    shimStyle.height = (shadowSize.height)+'px';
                }
            }else if(shim){
                if(doShow){
                   shim.show();
                }
                shim.setSize(w, h);
                shim.setLeftTop(l, t);
            }
        }
    },

    
    destroy : function(){
        this.hideShim();
        if(this.shadow){
            this.shadow.hide();
        }
        this.removeAllListeners();
        Ext.removeNode(this.dom);
        delete this.dom;
    },

    remove : function(){
        this.destroy();
    },

    
    beginUpdate : function(){
        this.updating = true;
    },

    
    endUpdate : function(){
        this.updating = false;
        this.sync(true);
    },

    
    hideUnders : function(negOffset){
        if(this.shadow){
            this.shadow.hide();
        }
        this.hideShim();
    },

    
    constrainXY : function(){
        if(this.constrain){
            var vw = Ext.lib.Dom.getViewWidth(),
                vh = Ext.lib.Dom.getViewHeight();
            var s = Ext.getDoc().getScroll();

            var xy = this.getXY();
            var x = xy[0], y = xy[1];
            var so = this.shadowOffset;
            var w = this.dom.offsetWidth+so, h = this.dom.offsetHeight+so;
            
            var moved = false;
            
            if((x + w) > vw+s.left){
                x = vw - w - so;
                moved = true;
            }
            if((y + h) > vh+s.top){
                y = vh - h - so;
                moved = true;
            }
            
            if(x < s.left){
                x = s.left;
                moved = true;
            }
            if(y < s.top){
                y = s.top;
                moved = true;
            }
            if(moved){
                if(this.avoidY){
                    var ay = this.avoidY;
                    if(y <= ay && (y+h) >= ay){
                        y = ay-h-5;
                    }
                }
                xy = [x, y];
                this.storeXY(xy);
                supr.setXY.call(this, xy);
                this.sync();
            }
        }
        return this;
    },

    isVisible : function(){
        return this.visible;
    },

    
    showAction : function(){
        this.visible = true; 
        if(this.useDisplay === true){
            this.setDisplayed('');
        }else if(this.lastXY){
            supr.setXY.call(this, this.lastXY);
        }else if(this.lastLT){
            supr.setLeftTop.call(this, this.lastLT[0], this.lastLT[1]);
        }
    },

    
    hideAction : function(){
        this.visible = false;
        if(this.useDisplay === true){
            this.setDisplayed(false);
        }else{
            this.setLeftTop(-10000,-10000);
        }
    },

    
    setVisible : function(v, a, d, c, e){
        if(v){
            this.showAction();
        }
        if(a && v){
            var cb = function(){
                this.sync(true);
                if(c){
                    c();
                }
            }.createDelegate(this);
            supr.setVisible.call(this, true, true, d, cb, e);
        }else{
            if(!v){
                this.hideUnders(true);
            }
            var cb = c;
            if(a){
                cb = function(){
                    this.hideAction();
                    if(c){
                        c();
                    }
                }.createDelegate(this);
            }
            supr.setVisible.call(this, v, a, d, cb, e);
            if(v){
                this.sync(true);
            }else if(!a){
                this.hideAction();
            }
        }
        return this;
    },

    storeXY : function(xy){
        delete this.lastLT;
        this.lastXY = xy;
    },

    storeLeftTop : function(left, top){
        delete this.lastXY;
        this.lastLT = [left, top];
    },

    
    beforeFx : function(){
        this.beforeAction();
        return Ext.Layer.superclass.beforeFx.apply(this, arguments);
    },

    
    afterFx : function(){
        Ext.Layer.superclass.afterFx.apply(this, arguments);
        this.sync(this.isVisible());
    },

    
    beforeAction : function(){
        if(!this.updating && this.shadow){
            this.shadow.hide();
        }
    },

    
    setLeft : function(left){
        this.storeLeftTop(left, this.getTop(true));
        supr.setLeft.apply(this, arguments);
        this.sync();
        return this;
    },

    setTop : function(top){
        this.storeLeftTop(this.getLeft(true), top);
        supr.setTop.apply(this, arguments);
        this.sync();
        return this;
    },

    setLeftTop : function(left, top){
        this.storeLeftTop(left, top);
        supr.setLeftTop.apply(this, arguments);
        this.sync();
        return this;
    },

    setXY : function(xy, a, d, c, e){
        this.fixDisplay();
        this.beforeAction();
        this.storeXY(xy);
        var cb = this.createCB(c);
        supr.setXY.call(this, xy, a, d, cb, e);
        if(!a){
            cb();
        }
        return this;
    },

    
    createCB : function(c){
        var el = this;
        return function(){
            el.constrainXY();
            el.sync(true);
            if(c){
                c();
            }
        };
    },

    
    setX : function(x, a, d, c, e){
        this.setXY([x, this.getY()], a, d, c, e);
        return this;
    },

    
    setY : function(y, a, d, c, e){
        this.setXY([this.getX(), y], a, d, c, e);
        return this;
    },

    
    setSize : function(w, h, a, d, c, e){
        this.beforeAction();
        var cb = this.createCB(c);
        supr.setSize.call(this, w, h, a, d, cb, e);
        if(!a){
            cb();
        }
        return this;
    },

    
    setWidth : function(w, a, d, c, e){
        this.beforeAction();
        var cb = this.createCB(c);
        supr.setWidth.call(this, w, a, d, cb, e);
        if(!a){
            cb();
        }
        return this;
    },

    
    setHeight : function(h, a, d, c, e){
        this.beforeAction();
        var cb = this.createCB(c);
        supr.setHeight.call(this, h, a, d, cb, e);
        if(!a){
            cb();
        }
        return this;
    },

    
    setBounds : function(x, y, w, h, a, d, c, e){
        this.beforeAction();
        var cb = this.createCB(c);
        if(!a){
            this.storeXY([x, y]);
            supr.setXY.call(this, [x, y]);
            supr.setSize.call(this, w, h, a, d, cb, e);
            cb();
        }else{
            supr.setBounds.call(this, x, y, w, h, a, d, cb, e);
        }
        return this;
    },

    
    setZIndex : function(zindex){
        this.zindex = zindex;
        this.setStyle('z-index', zindex + 2);
        if(this.shadow){
            this.shadow.setZIndex(zindex + 1);
        }
        if(this.shim){
            this.shim.setStyle('z-index', zindex);
        }
        return this;
    }
});
})();

Ext.Shadow = function(config){
    Ext.apply(this, config);
    if(typeof this.mode != "string"){
        this.mode = this.defaultMode;
    }
    var o = this.offset, a = {h: 0};
    var rad = Math.floor(this.offset/2);
    switch(this.mode.toLowerCase()){ 
        case "drop":
            a.w = 0;
            a.l = a.t = o;
            a.t -= 1;
            if(Ext.isIE){
                a.l -= this.offset + rad;
                a.t -= this.offset + rad;
                a.w -= rad;
                a.h -= rad;
                a.t += 1;
            }
        break;
        case "sides":
            a.w = (o*2);
            a.l = -o;
            a.t = o-1;
            if(Ext.isIE){
                a.l -= (this.offset - rad);
                a.t -= this.offset + rad;
                a.l += 1;
                a.w -= (this.offset - rad)*2;
                a.w -= rad + 1;
                a.h -= 1;
            }
        break;
        case "frame":
            a.w = a.h = (o*2);
            a.l = a.t = -o;
            a.t += 1;
            a.h -= 2;
            if(Ext.isIE){
                a.l -= (this.offset - rad);
                a.t -= (this.offset - rad);
                a.l += 1;
                a.w -= (this.offset + rad + 1);
                a.h -= (this.offset + rad);
                a.h += 1;
            }
        break;
    };

    this.adjusts = a;
};

Ext.Shadow.prototype = {
    
    
    offset: 4,

    
    defaultMode: "drop",

    
    show : function(target){
        target = Ext.get(target);
        if(!this.el){
            this.el = Ext.Shadow.Pool.pull();
            if(this.el.dom.nextSibling != target.dom){
                this.el.insertBefore(target);
            }
        }
        this.el.setStyle("z-index", this.zIndex || parseInt(target.getStyle("z-index"), 10)-1);
        if(Ext.isIE){
            this.el.dom.style.filter="progid:DXImageTransform.Microsoft.alpha(opacity=50) progid:DXImageTransform.Microsoft.Blur(pixelradius="+(this.offset)+")";
        }
        this.realign(
            target.getLeft(true),
            target.getTop(true),
            target.getWidth(),
            target.getHeight()
        );
        this.el.dom.style.display = "block";
    },

    
    isVisible : function(){
        return this.el ? true : false;  
    },

    
    realign : function(l, t, w, h){
        if(!this.el){
            return;
        }
        var a = this.adjusts, d = this.el.dom, s = d.style;
        var iea = 0;
        s.left = (l+a.l)+"px";
        s.top = (t+a.t)+"px";
        var sw = (w+a.w), sh = (h+a.h), sws = sw +"px", shs = sh + "px";
        if(s.width != sws || s.height != shs){
            s.width = sws;
            s.height = shs;
            if(!Ext.isIE){
                var cn = d.childNodes;
                var sww = Math.max(0, (sw-12))+"px";
                cn[0].childNodes[1].style.width = sww;
                cn[1].childNodes[1].style.width = sww;
                cn[2].childNodes[1].style.width = sww;
                cn[1].style.height = Math.max(0, (sh-12))+"px";
            }
        }
    },

    
    hide : function(){
        if(this.el){
            this.el.dom.style.display = "none";
            Ext.Shadow.Pool.push(this.el);
            delete this.el;
        }
    },

    
    setZIndex : function(z){
        this.zIndex = z;
        if(this.el){
            this.el.setStyle("z-index", z);
        }
    }
};


Ext.Shadow.Pool = function(){
    var p = [];
    var markup = Ext.isIE ?
                 '<div class="x-ie-shadow"></div>' :
                 '<div class="x-shadow"><div class="xst"><div class="xstl"></div><div class="xstc"></div><div class="xstr"></div></div><div class="xsc"><div class="xsml"></div><div class="xsmc"></div><div class="xsmr"></div></div><div class="xsb"><div class="xsbl"></div><div class="xsbc"></div><div class="xsbr"></div></div></div>';
    return {
        pull : function(){
            var sh = p.shift();
            if(!sh){
                sh = Ext.get(Ext.DomHelper.insertHtml("beforeBegin", document.body.firstChild, markup));
                sh.autoBoxAdjust = false;
            }
            return sh;
        },

        push : function(sh){
            p.push(sh);
        }
    };
}();
Ext.BoxComponent = Ext.extend(Ext.Component, {

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    

    
    initComponent : function(){
        Ext.BoxComponent.superclass.initComponent.call(this);
        this.addEvents(
            
            'resize',
            
            'move'
        );
    },

    
    boxReady : false,
    
    deferHeight: false,

    
    setSize : function(w, h){

        
        if(typeof w == 'object'){
            h = w.height;
            w = w.width;
        }
        if (Ext.isDefined(w) && Ext.isDefined(this.boxMinWidth) && (w < this.boxMinWidth)) {
            w = this.boxMinWidth;
        }
        if (Ext.isDefined(h) && Ext.isDefined(this.boxMinHeight) && (h < this.boxMinHeight)) {
            h = this.boxMinHeight;
        }
        if (Ext.isDefined(w) && Ext.isDefined(this.boxMaxWidth) && (w > this.boxMaxWidth)) {
            w = this.boxMaxWidth;
        }
        if (Ext.isDefined(h) && Ext.isDefined(this.boxMaxHeight) && (h > this.boxMaxHeight)) {
            h = this.boxMaxHeight;
        }
        
        if(!this.boxReady){
            this.width  = w;
            this.height = h;
            return this;
        }

        
        if(this.cacheSizes !== false && this.lastSize && this.lastSize.width == w && this.lastSize.height == h){
            return this;
        }
        this.lastSize = {width: w, height: h};
        var adj = this.adjustSize(w, h),
            aw = adj.width,
            ah = adj.height,
            rz;
        if(aw !== undefined || ah !== undefined){ 
            rz = this.getResizeEl();
            if(!this.deferHeight && aw !== undefined && ah !== undefined){
                rz.setSize(aw, ah);
            }else if(!this.deferHeight && ah !== undefined){
                rz.setHeight(ah);
            }else if(aw !== undefined){
                rz.setWidth(aw);
            }
            this.onResize(aw, ah, w, h);
            this.fireEvent('resize', this, aw, ah, w, h);
        }
        return this;
    },

    
    setWidth : function(width){
        return this.setSize(width);
    },

    
    setHeight : function(height){
        return this.setSize(undefined, height);
    },

    
    getSize : function(){
        return this.getResizeEl().getSize();
    },

    
    getWidth : function(){
        return this.getResizeEl().getWidth();
    },

    
    getHeight : function(){
        return this.getResizeEl().getHeight();
    },

    
    getOuterSize : function(){
        var el = this.getResizeEl();
        return {width: el.getWidth() + el.getMargins('lr'),
                height: el.getHeight() + el.getMargins('tb')};
    },

    
    getPosition : function(local){
        var el = this.getPositionEl();
        if(local === true){
            return [el.getLeft(true), el.getTop(true)];
        }
        return this.xy || el.getXY();
    },

    
    getBox : function(local){
        var pos = this.getPosition(local);
        var s = this.getSize();
        s.x = pos[0];
        s.y = pos[1];
        return s;
    },

    
    updateBox : function(box){
        this.setSize(box.width, box.height);
        this.setPagePosition(box.x, box.y);
        return this;
    },

    
    getResizeEl : function(){
        return this.resizeEl || this.el;
    },

    
    setAutoScroll : function(scroll){
        if(this.rendered){
            this.getContentTarget().setOverflow(scroll ? 'auto' : '');
        }
        this.autoScroll = scroll;
        return this;
    },

    
    setPosition : function(x, y){
        if(x && typeof x[1] == 'number'){
            y = x[1];
            x = x[0];
        }
        this.x = x;
        this.y = y;
        if(!this.boxReady){
            return this;
        }
        var adj = this.adjustPosition(x, y);
        var ax = adj.x, ay = adj.y;

        var el = this.getPositionEl();
        if(ax !== undefined || ay !== undefined){
            if(ax !== undefined && ay !== undefined){
                el.setLeftTop(ax, ay);
            }else if(ax !== undefined){
                el.setLeft(ax);
            }else if(ay !== undefined){
                el.setTop(ay);
            }
            this.onPosition(ax, ay);
            this.fireEvent('move', this, ax, ay);
        }
        return this;
    },

    
    setPagePosition : function(x, y){
        if(x && typeof x[1] == 'number'){
            y = x[1];
            x = x[0];
        }
        this.pageX = x;
        this.pageY = y;
        if(!this.boxReady){
            return;
        }
        if(x === undefined || y === undefined){ 
            return;
        }
        var p = this.getPositionEl().translatePoints(x, y);
        this.setPosition(p.left, p.top);
        return this;
    },

    
    afterRender : function(){
        Ext.BoxComponent.superclass.afterRender.call(this);
        if(this.resizeEl){
            this.resizeEl = Ext.get(this.resizeEl);
        }
        if(this.positionEl){
            this.positionEl = Ext.get(this.positionEl);
        }
        this.boxReady = true;
        Ext.isDefined(this.autoScroll) && this.setAutoScroll(this.autoScroll);
        this.setSize(this.width, this.height);
        if(this.x || this.y){
            this.setPosition(this.x, this.y);
        }else if(this.pageX || this.pageY){
            this.setPagePosition(this.pageX, this.pageY);
        }
    },

    
    syncSize : function(){
        delete this.lastSize;
        this.setSize(this.autoWidth ? undefined : this.getResizeEl().getWidth(), this.autoHeight ? undefined : this.getResizeEl().getHeight());
        return this;
    },

    
    onResize : function(adjWidth, adjHeight, rawWidth, rawHeight){
    },

    
    onPosition : function(x, y){

    },

    
    adjustSize : function(w, h){
        if(this.autoWidth){
            w = 'auto';
        }
        if(this.autoHeight){
            h = 'auto';
        }
        return {width : w, height: h};
    },

    
    adjustPosition : function(x, y){
        return {x : x, y: y};
    }
});
Ext.reg('box', Ext.BoxComponent);



Ext.Spacer = Ext.extend(Ext.BoxComponent, {
    autoEl:'div'
});
Ext.reg('spacer', Ext.Spacer);
Ext.SplitBar = function(dragElement, resizingElement, orientation, placement, existingProxy){

    
    this.el = Ext.get(dragElement, true);
    this.el.dom.unselectable = "on";
    
    this.resizingEl = Ext.get(resizingElement, true);

    
    this.orientation = orientation || Ext.SplitBar.HORIZONTAL;

    
    
    this.minSize = 0;

    
    this.maxSize = 2000;

    
    this.animate = false;

    
    this.useShim = false;

    
    this.shim = null;

    if(!existingProxy){
        
        this.proxy = Ext.SplitBar.createProxy(this.orientation);
    }else{
        this.proxy = Ext.get(existingProxy).dom;
    }
    
    this.dd = new Ext.dd.DDProxy(this.el.dom.id, "XSplitBars", {dragElId : this.proxy.id});

    
    this.dd.b4StartDrag = this.onStartProxyDrag.createDelegate(this);

    
    this.dd.endDrag = this.onEndProxyDrag.createDelegate(this);

    
    this.dragSpecs = {};

    
    this.adapter = new Ext.SplitBar.BasicLayoutAdapter();
    this.adapter.init(this);

    if(this.orientation == Ext.SplitBar.HORIZONTAL){
        
        this.placement = placement || (this.el.getX() > this.resizingEl.getX() ? Ext.SplitBar.LEFT : Ext.SplitBar.RIGHT);
        this.el.addClass("x-splitbar-h");
    }else{
        
        this.placement = placement || (this.el.getY() > this.resizingEl.getY() ? Ext.SplitBar.TOP : Ext.SplitBar.BOTTOM);
        this.el.addClass("x-splitbar-v");
    }

    this.addEvents(
        
        "resize",
        
        "moved",
        
        "beforeresize",

        "beforeapply"
    );

    Ext.SplitBar.superclass.constructor.call(this);
};

Ext.extend(Ext.SplitBar, Ext.util.Observable, {
    onStartProxyDrag : function(x, y){
        this.fireEvent("beforeresize", this);
        this.overlay =  Ext.DomHelper.append(document.body,  {cls: "x-drag-overlay", html: "&#160;"}, true);
        this.overlay.unselectable();
        this.overlay.setSize(Ext.lib.Dom.getViewWidth(true), Ext.lib.Dom.getViewHeight(true));
        this.overlay.show();
        Ext.get(this.proxy).setDisplayed("block");
        var size = this.adapter.getElementSize(this);
        this.activeMinSize = this.getMinimumSize();
        this.activeMaxSize = this.getMaximumSize();
        var c1 = size - this.activeMinSize;
        var c2 = Math.max(this.activeMaxSize - size, 0);
        if(this.orientation == Ext.SplitBar.HORIZONTAL){
            this.dd.resetConstraints();
            this.dd.setXConstraint(
                this.placement == Ext.SplitBar.LEFT ? c1 : c2,
                this.placement == Ext.SplitBar.LEFT ? c2 : c1,
                this.tickSize
            );
            this.dd.setYConstraint(0, 0);
        }else{
            this.dd.resetConstraints();
            this.dd.setXConstraint(0, 0);
            this.dd.setYConstraint(
                this.placement == Ext.SplitBar.TOP ? c1 : c2,
                this.placement == Ext.SplitBar.TOP ? c2 : c1,
                this.tickSize
            );
         }
        this.dragSpecs.startSize = size;
        this.dragSpecs.startPoint = [x, y];
        Ext.dd.DDProxy.prototype.b4StartDrag.call(this.dd, x, y);
    },

    
    onEndProxyDrag : function(e){
        Ext.get(this.proxy).setDisplayed(false);
        var endPoint = Ext.lib.Event.getXY(e);
        if(this.overlay){
            Ext.destroy(this.overlay);
            delete this.overlay;
        }
        var newSize;
        if(this.orientation == Ext.SplitBar.HORIZONTAL){
            newSize = this.dragSpecs.startSize +
                (this.placement == Ext.SplitBar.LEFT ?
                    endPoint[0] - this.dragSpecs.startPoint[0] :
                    this.dragSpecs.startPoint[0] - endPoint[0]
                );
        }else{
            newSize = this.dragSpecs.startSize +
                (this.placement == Ext.SplitBar.TOP ?
                    endPoint[1] - this.dragSpecs.startPoint[1] :
                    this.dragSpecs.startPoint[1] - endPoint[1]
                );
        }
        newSize = Math.min(Math.max(newSize, this.activeMinSize), this.activeMaxSize);
        if(newSize != this.dragSpecs.startSize){
            if(this.fireEvent('beforeapply', this, newSize) !== false){
                this.adapter.setElementSize(this, newSize);
                this.fireEvent("moved", this, newSize);
                this.fireEvent("resize", this, newSize);
            }
        }
    },

    
    getAdapter : function(){
        return this.adapter;
    },

    
    setAdapter : function(adapter){
        this.adapter = adapter;
        this.adapter.init(this);
    },

    
    getMinimumSize : function(){
        return this.minSize;
    },

    
    setMinimumSize : function(minSize){
        this.minSize = minSize;
    },

    
    getMaximumSize : function(){
        return this.maxSize;
    },

    
    setMaximumSize : function(maxSize){
        this.maxSize = maxSize;
    },

    
    setCurrentSize : function(size){
        var oldAnimate = this.animate;
        this.animate = false;
        this.adapter.setElementSize(this, size);
        this.animate = oldAnimate;
    },

    
    destroy : function(removeEl){
        Ext.destroy(this.shim, Ext.get(this.proxy));
        this.dd.unreg();
        if(removeEl){
            this.el.remove();
        }
        this.purgeListeners();
    }
});


Ext.SplitBar.createProxy = function(dir){
    var proxy = new Ext.Element(document.createElement("div"));
    document.body.appendChild(proxy.dom);
    proxy.unselectable();
    var cls = 'x-splitbar-proxy';
    proxy.addClass(cls + ' ' + (dir == Ext.SplitBar.HORIZONTAL ? cls +'-h' : cls + '-v'));
    return proxy.dom;
};


Ext.SplitBar.BasicLayoutAdapter = function(){
};

Ext.SplitBar.BasicLayoutAdapter.prototype = {
    
    init : function(s){

    },
    
     getElementSize : function(s){
        if(s.orientation == Ext.SplitBar.HORIZONTAL){
            return s.resizingEl.getWidth();
        }else{
            return s.resizingEl.getHeight();
        }
    },

    
    setElementSize : function(s, newSize, onComplete){
        if(s.orientation == Ext.SplitBar.HORIZONTAL){
            if(!s.animate){
                s.resizingEl.setWidth(newSize);
                if(onComplete){
                    onComplete(s, newSize);
                }
            }else{
                s.resizingEl.setWidth(newSize, true, .1, onComplete, 'easeOut');
            }
        }else{

            if(!s.animate){
                s.resizingEl.setHeight(newSize);
                if(onComplete){
                    onComplete(s, newSize);
                }
            }else{
                s.resizingEl.setHeight(newSize, true, .1, onComplete, 'easeOut');
            }
        }
    }
};


Ext.SplitBar.AbsoluteLayoutAdapter = function(container){
    this.basic = new Ext.SplitBar.BasicLayoutAdapter();
    this.container = Ext.get(container);
};

Ext.SplitBar.AbsoluteLayoutAdapter.prototype = {
    init : function(s){
        this.basic.init(s);
    },

    getElementSize : function(s){
        return this.basic.getElementSize(s);
    },

    setElementSize : function(s, newSize, onComplete){
        this.basic.setElementSize(s, newSize, this.moveSplitter.createDelegate(this, [s]));
    },

    moveSplitter : function(s){
        var yes = Ext.SplitBar;
        switch(s.placement){
            case yes.LEFT:
                s.el.setX(s.resizingEl.getRight());
                break;
            case yes.RIGHT:
                s.el.setStyle("right", (this.container.getWidth() - s.resizingEl.getLeft()) + "px");
                break;
            case yes.TOP:
                s.el.setY(s.resizingEl.getBottom());
                break;
            case yes.BOTTOM:
                s.el.setY(s.resizingEl.getTop() - s.el.getHeight());
                break;
        }
    }
};


Ext.SplitBar.VERTICAL = 1;


Ext.SplitBar.HORIZONTAL = 2;


Ext.SplitBar.LEFT = 1;


Ext.SplitBar.RIGHT = 2;


Ext.SplitBar.TOP = 3;


Ext.SplitBar.BOTTOM = 4;

Ext.Container = Ext.extend(Ext.BoxComponent, {
    
    
    
    
    bufferResize: 50,

    
    
    


    
    autoDestroy : true,

    
    forceLayout: false,

    
    
    defaultType : 'panel',

    
    resizeEvent: 'resize',

    
    bubbleEvents: ['add', 'remove'],

    
    initComponent : function(){
        Ext.Container.superclass.initComponent.call(this);

        this.addEvents(
            
            'afterlayout',
            
            'beforeadd',
            
            'beforeremove',
            
            'add',
            
            'remove'
        );

        
        var items = this.items;
        if(items){
            delete this.items;
            this.add(items);
        }
    },

    
    initItems : function(){
        if(!this.items){
            this.items = new Ext.util.MixedCollection(false, this.getComponentId);
            this.getLayout(); 
        }
    },

    
    setLayout : function(layout){
        if(this.layout && this.layout != layout){
            this.layout.setContainer(null);
        }
        this.layout = layout;
        this.initItems();
        layout.setContainer(this);
    },

    afterRender: function(){
        
        
        Ext.Container.superclass.afterRender.call(this);
        if(!this.layout){
            this.layout = 'auto';
        }
        if(Ext.isObject(this.layout) && !this.layout.layout){
            this.layoutConfig = this.layout;
            this.layout = this.layoutConfig.type;
        }
        if(Ext.isString(this.layout)){
            this.layout = new Ext.Container.LAYOUTS[this.layout.toLowerCase()](this.layoutConfig);
        }
        this.setLayout(this.layout);

        
        if(this.activeItem !== undefined){
            var item = this.activeItem;
            delete this.activeItem;
            this.layout.setActiveItem(item);
        }

        
        if(!this.ownerCt){
            this.doLayout(false, true);
        }

        
        
        if(this.monitorResize === true){
            Ext.EventManager.onWindowResize(this.doLayout, this, [false]);
        }
    },

    
    getLayoutTarget : function(){
        return this.el;
    },

    
    getComponentId : function(comp){
        return comp.getItemId();
    },

    
    add : function(comp){
        this.initItems();
        var args = arguments.length > 1;
        if(args || Ext.isArray(comp)){
            var result = [];
            Ext.each(args ? arguments : comp, function(c){
                result.push(this.add(c));
            }, this);
            return result;
        }
        var c = this.lookupComponent(this.applyDefaults(comp));
        var index = this.items.length;
        if(this.fireEvent('beforeadd', this, c, index) !== false && this.onBeforeAdd(c) !== false){
            this.items.add(c);
            
            c.onAdded(this, index);
            this.onAdd(c);
            this.fireEvent('add', this, c, index);
        }
        return c;
    },

    onAdd : function(c){
        
    },

    
    onAdded : function(container, pos) {
        
        this.ownerCt = container;
        this.initRef();
        
        this.cascade(function(c){
            c.initRef();
        });
        this.fireEvent('added', this, container, pos);
    },

    
    insert : function(index, comp){
        this.initItems();
        var a = arguments, len = a.length;
        if(len > 2){
            var result = [];
            for(var i = len-1; i >= 1; --i) {
                result.push(this.insert(index, a[i]));
            }
            return result;
        }
        var c = this.lookupComponent(this.applyDefaults(comp));
        index = Math.min(index, this.items.length);
        if(this.fireEvent('beforeadd', this, c, index) !== false && this.onBeforeAdd(c) !== false){
            if(c.ownerCt == this){
                this.items.remove(c);
            }
            this.items.insert(index, c);
            c.onAdded(this, index);
            this.onAdd(c);
            this.fireEvent('add', this, c, index);
        }
        return c;
    },

    
    applyDefaults : function(c){
        var d = this.defaults;
        if(d){
            if(Ext.isFunction(d)){
                d = d.call(this, c);
            }
            if(Ext.isString(c)){
                c = Ext.ComponentMgr.get(c);
                Ext.apply(c, d);
            }else if(!c.events){
                Ext.applyIf(c, d);
            }else{
                Ext.apply(c, d);
            }
        }
        return c;
    },

    
    onBeforeAdd : function(item){
        if(item.ownerCt){
            item.ownerCt.remove(item, false);
        }
        if(this.hideBorders === true){
            item.border = (item.border === true);
        }
    },

    
    remove : function(comp, autoDestroy){
        this.initItems();
        var c = this.getComponent(comp);
        if(c && this.fireEvent('beforeremove', this, c) !== false){
            this.doRemove(c, autoDestroy);
            this.fireEvent('remove', this, c);
        }
        return c;
    },

    onRemove: function(c){
        
    },

    
    doRemove: function(c, autoDestroy){
        var l = this.layout,
            hasLayout = l && this.rendered;

        if(hasLayout){
            l.onRemove(c);
        }
        this.items.remove(c);
        c.onRemoved();
        this.onRemove(c);
        if(autoDestroy === true || (autoDestroy !== false && this.autoDestroy)){
            c.destroy();
        }
        if(hasLayout){
            l.afterRemove(c);
        }
    },

    
    removeAll: function(autoDestroy){
        this.initItems();
        var item, rem = [], items = [];
        this.items.each(function(i){
            rem.push(i);
        });
        for (var i = 0, len = rem.length; i < len; ++i){
            item = rem[i];
            this.remove(item, autoDestroy);
            if(item.ownerCt !== this){
                items.push(item);
            }
        }
        return items;
    },

    
    getComponent : function(comp){
        if(Ext.isObject(comp)){
            comp = comp.getItemId();
        }
        return this.items.get(comp);
    },

    
    lookupComponent : function(comp){
        if(Ext.isString(comp)){
            return Ext.ComponentMgr.get(comp);
        }else if(!comp.events){
            return this.createComponent(comp);
        }
        return comp;
    },

    
    createComponent : function(config, defaultType){
        if (config.render) {
            return config;
        }
        
        
        var c = Ext.create(Ext.apply({
            ownerCt: this
        }, config), defaultType || this.defaultType);
        delete c.initialConfig.ownerCt;
        delete c.ownerCt;
        return c;
    },

    
    canLayout : function() {
        var el = this.getVisibilityEl();
        return el && el.dom && !el.isStyle("display", "none");
    },

    

    doLayout : function(shallow, force){
        var rendered = this.rendered,
            forceLayout = force || this.forceLayout;

        if(this.collapsed || !this.canLayout()){
            this.deferLayout = this.deferLayout || !shallow;
            if(!forceLayout){
                return;
            }
            shallow = shallow && !this.deferLayout;
        } else {
            delete this.deferLayout;
        }
        if(rendered && this.layout){
            this.layout.layout();
        }
        if(shallow !== true && this.items){
            var cs = this.items.items;
            for(var i = 0, len = cs.length; i < len; i++){
                var c = cs[i];
                if(c.doLayout){
                    c.doLayout(false, forceLayout);
                }
            }
        }
        if(rendered){
            this.onLayout(shallow, forceLayout);
        }
        
        this.hasLayout = true;
        delete this.forceLayout;
    },

    onLayout : Ext.emptyFn,

    
    shouldBufferLayout: function(){
        
        var hl = this.hasLayout;
        if(this.ownerCt){
            
            return hl ? !this.hasLayoutPending() : false;
        }
        
        return hl;
    },

    
    hasLayoutPending: function(){
        
        var pending = false;
        this.ownerCt.bubble(function(c){
            if(c.layoutPending){
                pending = true;
                return false;
            }
        });
        return pending;
    },

    onShow : function(){
        
        Ext.Container.superclass.onShow.call(this);
        
        if(Ext.isDefined(this.deferLayout)){
            delete this.deferLayout;
            this.doLayout(true);
        }
    },

    
    getLayout : function(){
        if(!this.layout){
            var layout = new Ext.layout.AutoLayout(this.layoutConfig);
            this.setLayout(layout);
        }
        return this.layout;
    },

    
    beforeDestroy : function(){
        var c;
        if(this.items){
            while(c = this.items.first()){
                this.doRemove(c, true);
            }
        }
        if(this.monitorResize){
            Ext.EventManager.removeResizeListener(this.doLayout, this);
        }
        Ext.destroy(this.layout);
        Ext.Container.superclass.beforeDestroy.call(this);
    },

    
    bubble : function(fn, scope, args){
        var p = this;
        while(p){
            if(fn.apply(scope || p, args || [p]) === false){
                break;
            }
            p = p.ownerCt;
        }
        return this;
    },

    
    cascade : function(fn, scope, args){
        if(fn.apply(scope || this, args || [this]) !== false){
            if(this.items){
                var cs = this.items.items;
                for(var i = 0, len = cs.length; i < len; i++){
                    if(cs[i].cascade){
                        cs[i].cascade(fn, scope, args);
                    }else{
                        fn.apply(scope || cs[i], args || [cs[i]]);
                    }
                }
            }
        }
        return this;
    },

    
    findById : function(id){
        var m, ct = this;
        this.cascade(function(c){
            if(ct != c && c.id === id){
                m = c;
                return false;
            }
        });
        return m || null;
    },

    
    findByType : function(xtype, shallow){
        return this.findBy(function(c){
            return c.isXType(xtype, shallow);
        });
    },

    
    find : function(prop, value){
        return this.findBy(function(c){
            return c[prop] === value;
        });
    },

    
    findBy : function(fn, scope){
        var m = [], ct = this;
        this.cascade(function(c){
            if(ct != c && fn.call(scope || c, c, ct) === true){
                m.push(c);
            }
        });
        return m;
    },

    
    get : function(key){
        return this.items.get(key);
    }
});

Ext.Container.LAYOUTS = {};
Ext.reg('container', Ext.Container);

Ext.layout.ContainerLayout = Ext.extend(Object, {
    
    

    

    
    monitorResize:false,
    
    activeItem : null,

    constructor : function(config){
        this.id = Ext.id(null, 'ext-layout-');
        Ext.apply(this, config);
    },

    type: 'container',

    
    IEMeasureHack : function(target, viewFlag) {
        var tChildren = target.dom.childNodes, tLen = tChildren.length, c, d = [], e, i, ret;
        for (i = 0 ; i < tLen ; i++) {
            c = tChildren[i];
            e = Ext.get(c);
            if (e) {
                d[i] = e.getStyle('display');
                e.setStyle({display: 'none'});
            }
        }
        ret = target ? target.getViewSize(viewFlag) : {};
        for (i = 0 ; i < tLen ; i++) {
            c = tChildren[i];
            e = Ext.get(c);
            if (e) {
                e.setStyle({display: d[i]});
            }
        }
        return ret;
    },

    
    getLayoutTargetSize : Ext.EmptyFn,

    
    layout : function(){
        var ct = this.container, target = ct.getLayoutTarget();
        if(!(this.hasLayout || Ext.isEmpty(this.targetCls))){
            target.addClass(this.targetCls);
        }
        this.onLayout(ct, target);
        ct.fireEvent('afterlayout', ct, this);
    },

    
    onLayout : function(ct, target){
        this.renderAll(ct, target);
    },

    
    isValidParent : function(c, target){
        return target && c.getPositionEl().dom.parentNode == (target.dom || target);
    },

    
    renderAll : function(ct, target){
        var items = ct.items.items, i, c, len = items.length;
        for(i = 0; i < len; i++) {
            c = items[i];
            if(c && (!c.rendered || !this.isValidParent(c, target))){
                this.renderItem(c, i, target);
            }
        }
    },

    
    renderItem : function(c, position, target){
        if (c) {
            if (!c.rendered) {
                c.render(target, position);
                this.configureItem(c, position);
            } else if (!this.isValidParent(c, target)) {
                if (Ext.isNumber(position)) {
                    position = target.dom.childNodes[position];
                }
                
                target.dom.insertBefore(c.getPositionEl().dom, position || null);
                c.container = target;
                this.configureItem(c, position);
            }
        }
    },

    
    
    getRenderedItems: function(ct){
        var t = ct.getLayoutTarget(), cti = ct.items.items, len = cti.length, i, c, items = [];
        for (i = 0; i < len; i++) {
            if((c = cti[i]).rendered && this.isValidParent(c, t)){
                items.push(c);
            }
        };
        return items;
    },

    
    configureItem: function(c, position){
        if (this.extraCls) {
            var t = c.getPositionEl ? c.getPositionEl() : c;
            t.addClass(this.extraCls);
        }
        
        
        if (c.doLayout && this.forceLayout) {
            c.doLayout();
        }
        if (this.renderHidden && c != this.activeItem) {
            c.hide();
        }
    },

    onRemove: function(c){
        if(this.activeItem == c){
            delete this.activeItem;
        }
        if(c.rendered && this.extraCls){
            var t = c.getPositionEl ? c.getPositionEl() : c;
            t.removeClass(this.extraCls);
        }
    },

    afterRemove: function(c){
        if(c.removeRestore){
            c.removeMode = 'container';
            delete c.removeRestore;
        }
    },

    
    onResize: function(){
        var ct = this.container,
            b;
        if(ct.collapsed){
            return;
        }
        if(b = ct.bufferResize && ct.shouldBufferLayout()){
            if(!this.resizeTask){
                this.resizeTask = new Ext.util.DelayedTask(this.runLayout, this);
                this.resizeBuffer = Ext.isNumber(b) ? b : 50;
            }
            ct.layoutPending = true;
            this.resizeTask.delay(this.resizeBuffer);
        }else{
            this.runLayout();
        }
    },

    runLayout: function(){
        var ct = this.container;
        this.layout();
        ct.onLayout();
        delete ct.layoutPending;
    },

    
    setContainer : function(ct){
        
        if(this.monitorResize && ct != this.container){
            var old = this.container;
            if(old){
                old.un(old.resizeEvent, this.onResize, this);
            }
            if(ct){
                ct.on(ct.resizeEvent, this.onResize, this);
            }
        }
        this.container = ct;
    },

    
    parseMargins : function(v){
        if (Ext.isNumber(v)) {
            v = v.toString();
        }
        var ms  = v.split(' '),
            len = ms.length;
            
        if (len == 1) {
            ms[1] = ms[2] = ms[3] = ms[0];
        } else if(len == 2) {
            ms[2] = ms[0];
            ms[3] = ms[1];
        } else if(len == 3) {
            ms[3] = ms[1];
        }
        
        return {
            top   :parseInt(ms[0], 10) || 0,
            right :parseInt(ms[1], 10) || 0,
            bottom:parseInt(ms[2], 10) || 0,
            left  :parseInt(ms[3], 10) || 0
        };
    },

    
    fieldTpl: (function() {
        var t = new Ext.Template(
            '<div class="x-form-item {itemCls}" tabIndex="-1">',
                '<label for="{id}" style="{labelStyle}" class="x-form-item-label">{label}{labelSeparator}</label>',
                '<div class="x-form-element" id="x-form-el-{id}" style="{elementStyle}">',
                '</div><div class="{clearCls}"></div>',
            '</div>'
        );
        t.disableFormats = true;
        return t.compile();
    })(),

    
    destroy : function(){
        
        if(this.resizeTask && this.resizeTask.cancel){
            this.resizeTask.cancel();
        }
        if(!Ext.isEmpty(this.targetCls)){
            var target = this.container.getLayoutTarget();
            if(target){
                target.removeClass(this.targetCls);
            }
        }
    }
});
Ext.layout.AutoLayout = Ext.extend(Ext.layout.ContainerLayout, {
    type: 'auto',

    monitorResize: true,

    onLayout : function(ct, target){
        Ext.layout.AutoLayout.superclass.onLayout.call(this, ct, target);
        var cs = this.getRenderedItems(ct), len = cs.length, i, c;
        for(i = 0; i < len; i++){
            c = cs[i];
            if (c.doLayout){
                
                c.doLayout(true);
            }
        }
    }
});

Ext.Container.LAYOUTS['auto'] = Ext.layout.AutoLayout;

Ext.layout.FitLayout = Ext.extend(Ext.layout.ContainerLayout, {
    
    monitorResize:true,

    type: 'fit',

    getLayoutTargetSize : function() {
        var target = this.container.getLayoutTarget();
        if (!target) {
            return {};
        }
        
        return target.getStyleSize();
    },

    
    onLayout : function(ct, target){
        Ext.layout.FitLayout.superclass.onLayout.call(this, ct, target);
        if(!ct.collapsed){
            this.setItemSize(this.activeItem || ct.items.itemAt(0), this.getLayoutTargetSize());
        }
    },

    
    setItemSize : function(item, size){
        if(item && size.height > 0){ 
            item.setSize(size);
        }
    }
});
Ext.Container.LAYOUTS['fit'] = Ext.layout.FitLayout;
Ext.layout.CardLayout = Ext.extend(Ext.layout.FitLayout, {
    
    deferredRender : false,

    
    layoutOnCardChange : false,

    
    
    renderHidden : true,

    type: 'card',

    
    setActiveItem : function(item){
        var ai = this.activeItem,
            ct = this.container;
        item = ct.getComponent(item);

        
        if(item && ai != item){

            
            if(ai){
                ai.hide();
                if (ai.hidden !== true) {
                    return false;
                }
                ai.fireEvent('deactivate', ai);
            }

            var layout = item.doLayout && (this.layoutOnCardChange || !item.rendered);

            
            this.activeItem = item;

            
            
            delete item.deferLayout;

            
            item.show();

            this.layout();

            if(layout){
                item.doLayout();
            }
            item.fireEvent('activate', item);
        }
    },

    
    renderAll : function(ct, target){
        if(this.deferredRender){
            this.renderItem(this.activeItem, undefined, target);
        }else{
            Ext.layout.CardLayout.superclass.renderAll.call(this, ct, target);
        }
    }
});
Ext.Container.LAYOUTS['card'] = Ext.layout.CardLayout;

Ext.layout.AnchorLayout = Ext.extend(Ext.layout.ContainerLayout, {
    

    
    monitorResize : true,

    type : 'anchor',

    
    defaultAnchor : '100%',

    parseAnchorRE : /^(r|right|b|bottom)$/i,

    getLayoutTargetSize : function() {
        var target = this.container.getLayoutTarget();
        if (!target) {
            return {};
        }
        
        return target.getStyleSize();
    },

    
    onLayout : function(ct, target){
        Ext.layout.AnchorLayout.superclass.onLayout.call(this, ct, target);
        var size = this.getLayoutTargetSize();

        var w = size.width, h = size.height;

        if(w < 20 && h < 20){
            return;
        }

        
        var aw, ah;
        if(ct.anchorSize){
            if(typeof ct.anchorSize == 'number'){
                aw = ct.anchorSize;
            }else{
                aw = ct.anchorSize.width;
                ah = ct.anchorSize.height;
            }
        }else{
            aw = ct.initialConfig.width;
            ah = ct.initialConfig.height;
        }

        var cs = this.getRenderedItems(ct), len = cs.length, i, c, a, cw, ch, el, vs, boxes = [];
        for(i = 0; i < len; i++){
            c = cs[i];
            el = c.getPositionEl();

            
            if (!c.anchor && c.items && !Ext.isNumber(c.width) && !(Ext.isIE6 && Ext.isStrict)){
                c.anchor = this.defaultAnchor;
            }

            if(c.anchor){
                a = c.anchorSpec;
                if(!a){ 
                    vs = c.anchor.split(' ');
                    c.anchorSpec = a = {
                        right: this.parseAnchor(vs[0], c.initialConfig.width, aw),
                        bottom: this.parseAnchor(vs[1], c.initialConfig.height, ah)
                    };
                }
                cw = a.right ? this.adjustWidthAnchor(a.right(w) - el.getMargins('lr'), c) : undefined;
                ch = a.bottom ? this.adjustHeightAnchor(a.bottom(h) - el.getMargins('tb'), c) : undefined;

                if(cw || ch){
                    boxes.push({
                        comp: c,
                        width: cw || undefined,
                        height: ch || undefined
                    });
                }
            }
        }
        for (i = 0, len = boxes.length; i < len; i++) {
            c = boxes[i];
            c.comp.setSize(c.width, c.height);
        }
    },

    
    parseAnchor : function(a, start, cstart){
        if(a && a != 'none'){
            var last;
            
            if(this.parseAnchorRE.test(a)){
                var diff = cstart - start;
                return function(v){
                    if(v !== last){
                        last = v;
                        return v - diff;
                    }
                }
            
            }else if(a.indexOf('%') != -1){
                var ratio = parseFloat(a.replace('%', ''))*.01;
                return function(v){
                    if(v !== last){
                        last = v;
                        return Math.floor(v*ratio);
                    }
                }
            
            }else{
                a = parseInt(a, 10);
                if(!isNaN(a)){
                    return function(v){
                        if(v !== last){
                            last = v;
                            return v + a;
                        }
                    }
                }
            }
        }
        return false;
    },

    
    adjustWidthAnchor : function(value, comp){
        return value;
    },

    
    adjustHeightAnchor : function(value, comp){
        return value;
    }

    
});
Ext.Container.LAYOUTS['anchor'] = Ext.layout.AnchorLayout;

Ext.layout.ColumnLayout = Ext.extend(Ext.layout.ContainerLayout, {
    
    monitorResize:true,

    type: 'column',

    extraCls: 'x-column',

    scrollOffset : 0,

    

    targetCls: 'x-column-layout-ct',

    isValidParent : function(c, target){
        return this.innerCt && c.getPositionEl().dom.parentNode == this.innerCt.dom;
    },

    getLayoutTargetSize : function() {
        var target = this.container.getLayoutTarget(), ret;
        if (target) {
            ret = target.getViewSize();

            
            
            
            if (Ext.isIE && Ext.isStrict && ret.width == 0){
                ret =  target.getStyleSize();
            }

            ret.width -= target.getPadding('lr');
            ret.height -= target.getPadding('tb');
        }
        return ret;
    },

    renderAll : function(ct, target) {
        if(!this.innerCt){
            
            
            this.innerCt = target.createChild({cls:'x-column-inner'});
            this.innerCt.createChild({cls:'x-clear'});
        }
        Ext.layout.ColumnLayout.superclass.renderAll.call(this, ct, this.innerCt);
    },

    
    onLayout : function(ct, target){
        var cs = ct.items.items,
            len = cs.length,
            c,
            i,
            m,
            margins = [];

        this.renderAll(ct, target);

        var size = this.getLayoutTargetSize();

        if(size.width < 1 && size.height < 1){ 
            return;
        }

        var w = size.width - this.scrollOffset,
            h = size.height,
            pw = w;

        this.innerCt.setWidth(w);

        
        

        for(i = 0; i < len; i++){
            c = cs[i];
            m = c.getPositionEl().getMargins('lr');
            margins[i] = m;
            if(!c.columnWidth){
                pw -= (c.getWidth() + m);
            }
        }

        pw = pw < 0 ? 0 : pw;

        for(i = 0; i < len; i++){
            c = cs[i];
            m = margins[i];
            if(c.columnWidth){
                c.setSize(Math.floor(c.columnWidth * pw) - m);
            }
        }

        
        
        if (Ext.isIE) {
            if (i = target.getStyle('overflow') && i != 'hidden' && !this.adjustmentPass) {
                var ts = this.getLayoutTargetSize();
                if (ts.width != size.width){
                    this.adjustmentPass = true;
                    this.onLayout(ct, target);
                }
            }
        }
        delete this.adjustmentPass;
    }

    
});

Ext.Container.LAYOUTS['column'] = Ext.layout.ColumnLayout;

Ext.layout.BorderLayout = Ext.extend(Ext.layout.ContainerLayout, {
    
    monitorResize:true,
    
    rendered : false,

    type: 'border',

    targetCls: 'x-border-layout-ct',

    getLayoutTargetSize : function() {
        var target = this.container.getLayoutTarget();
        return target ? target.getViewSize() : {};
    },

    
    onLayout : function(ct, target){
        var collapsed, i, c, pos, items = ct.items.items, len = items.length;
        if(!this.rendered){
            collapsed = [];
            for(i = 0; i < len; i++) {
                c = items[i];
                pos = c.region;
                if(c.collapsed){
                    collapsed.push(c);
                }
                c.collapsed = false;
                if(!c.rendered){
                    c.render(target, i);
                    c.getPositionEl().addClass('x-border-panel');
                }
                this[pos] = pos != 'center' && c.split ?
                    new Ext.layout.BorderLayout.SplitRegion(this, c.initialConfig, pos) :
                    new Ext.layout.BorderLayout.Region(this, c.initialConfig, pos);
                this[pos].render(target, c);
            }
            this.rendered = true;
        }

        var size = this.getLayoutTargetSize();
        if(size.width < 20 || size.height < 20){ 
            if(collapsed){
                this.restoreCollapsed = collapsed;
            }
            return;
        }else if(this.restoreCollapsed){
            collapsed = this.restoreCollapsed;
            delete this.restoreCollapsed;
        }

        var w = size.width, h = size.height,
            centerW = w, centerH = h, centerY = 0, centerX = 0,
            n = this.north, s = this.south, west = this.west, e = this.east, c = this.center,
            b, m, totalWidth, totalHeight;
        if(!c && Ext.layout.BorderLayout.WARN !== false){
            throw 'No center region defined in BorderLayout ' + ct.id;
        }

        if(n && n.isVisible()){
            b = n.getSize();
            m = n.getMargins();
            b.width = w - (m.left+m.right);
            b.x = m.left;
            b.y = m.top;
            centerY = b.height + b.y + m.bottom;
            centerH -= centerY;
            n.applyLayout(b);
        }
        if(s && s.isVisible()){
            b = s.getSize();
            m = s.getMargins();
            b.width = w - (m.left+m.right);
            b.x = m.left;
            totalHeight = (b.height + m.top + m.bottom);
            b.y = h - totalHeight + m.top;
            centerH -= totalHeight;
            s.applyLayout(b);
        }
        if(west && west.isVisible()){
            b = west.getSize();
            m = west.getMargins();
            b.height = centerH - (m.top+m.bottom);
            b.x = m.left;
            b.y = centerY + m.top;
            totalWidth = (b.width + m.left + m.right);
            centerX += totalWidth;
            centerW -= totalWidth;
            west.applyLayout(b);
        }
        if(e && e.isVisible()){
            b = e.getSize();
            m = e.getMargins();
            b.height = centerH - (m.top+m.bottom);
            totalWidth = (b.width + m.left + m.right);
            b.x = w - totalWidth + m.left;
            b.y = centerY + m.top;
            centerW -= totalWidth;
            e.applyLayout(b);
        }
        if(c){
            m = c.getMargins();
            var centerBox = {
                x: centerX + m.left,
                y: centerY + m.top,
                width: centerW - (m.left+m.right),
                height: centerH - (m.top+m.bottom)
            };
            c.applyLayout(centerBox);
        }
        if(collapsed){
            for(i = 0, len = collapsed.length; i < len; i++){
                collapsed[i].collapse(false);
            }
        }
        if(Ext.isIE && Ext.isStrict){ 
            target.repaint();
        }
        
        if (i = target.getStyle('overflow') && i != 'hidden' && !this.adjustmentPass) {
            var ts = this.getLayoutTargetSize();
            if (ts.width != size.width || ts.height != size.height){
                this.adjustmentPass = true;
                this.onLayout(ct, target);
            }
        }
        delete this.adjustmentPass;
    },

    destroy: function() {
        var r = ['north', 'south', 'east', 'west'], i, region;
        for (i = 0; i < r.length; i++) {
            region = this[r[i]];
            if(region){
                if(region.destroy){
                    region.destroy();
                }else if (region.split){
                    region.split.destroy(true);
                }
            }
        }
        Ext.layout.BorderLayout.superclass.destroy.call(this);
    }

    
});


Ext.layout.BorderLayout.Region = function(layout, config, pos){
    Ext.apply(this, config);
    this.layout = layout;
    this.position = pos;
    this.state = {};
    if(typeof this.margins == 'string'){
        this.margins = this.layout.parseMargins(this.margins);
    }
    this.margins = Ext.applyIf(this.margins || {}, this.defaultMargins);
    if(this.collapsible){
        if(typeof this.cmargins == 'string'){
            this.cmargins = this.layout.parseMargins(this.cmargins);
        }
        if(this.collapseMode == 'mini' && !this.cmargins){
            this.cmargins = {left:0,top:0,right:0,bottom:0};
        }else{
            this.cmargins = Ext.applyIf(this.cmargins || {},
                pos == 'north' || pos == 'south' ? this.defaultNSCMargins : this.defaultEWCMargins);
        }
    }
};

Ext.layout.BorderLayout.Region.prototype = {
    
    
    
    
    
    
    collapsible : false,
    
    split:false,
    
    floatable: true,
    
    minWidth:50,
    
    minHeight:50,

    
    defaultMargins : {left:0,top:0,right:0,bottom:0},
    
    defaultNSCMargins : {left:5,top:5,right:5,bottom:5},
    
    defaultEWCMargins : {left:5,top:0,right:5,bottom:0},
    floatingZIndex: 100,

    
    isCollapsed : false,

    
    
    

    
    render : function(ct, p){
        this.panel = p;
        p.el.enableDisplayMode();
        this.targetEl = ct;
        this.el = p.el;

        var gs = p.getState, ps = this.position;
        p.getState = function(){
            return Ext.apply(gs.call(p) || {}, this.state);
        }.createDelegate(this);

        if(ps != 'center'){
            p.allowQueuedExpand = false;
            p.on({
                beforecollapse: this.beforeCollapse,
                collapse: this.onCollapse,
                beforeexpand: this.beforeExpand,
                expand: this.onExpand,
                hide: this.onHide,
                show: this.onShow,
                scope: this
            });
            if(this.collapsible || this.floatable){
                p.collapseEl = 'el';
                p.slideAnchor = this.getSlideAnchor();
            }
            if(p.tools && p.tools.toggle){
                p.tools.toggle.addClass('x-tool-collapse-'+ps);
                p.tools.toggle.addClassOnOver('x-tool-collapse-'+ps+'-over');
            }
        }
    },

    
    getCollapsedEl : function(){
        if(!this.collapsedEl){
            if(!this.toolTemplate){
                var tt = new Ext.Template(
                     '<div class="x-tool x-tool-{id}">&#160;</div>'
                );
                tt.disableFormats = true;
                tt.compile();
                Ext.layout.BorderLayout.Region.prototype.toolTemplate = tt;
            }
            this.collapsedEl = this.targetEl.createChild({
                cls: "x-layout-collapsed x-layout-collapsed-"+this.position,
                id: this.panel.id + '-xcollapsed'
            });
            this.collapsedEl.enableDisplayMode('block');

            if(this.collapseMode == 'mini'){
                this.collapsedEl.addClass('x-layout-cmini-'+this.position);
                this.miniCollapsedEl = this.collapsedEl.createChild({
                    cls: "x-layout-mini x-layout-mini-"+this.position, html: "&#160;"
                });
                this.miniCollapsedEl.addClassOnOver('x-layout-mini-over');
                this.collapsedEl.addClassOnOver("x-layout-collapsed-over");
                this.collapsedEl.on('click', this.onExpandClick, this, {stopEvent:true});
            }else {
                if(this.collapsible !== false && !this.hideCollapseTool) {
                    var t = this.toolTemplate.append(
                            this.collapsedEl.dom,
                            {id:'expand-'+this.position}, true);
                    t.addClassOnOver('x-tool-expand-'+this.position+'-over');
                    t.on('click', this.onExpandClick, this, {stopEvent:true});
                }
                if(this.floatable !== false || this.titleCollapse){
                   this.collapsedEl.addClassOnOver("x-layout-collapsed-over");
                   this.collapsedEl.on("click", this[this.floatable ? 'collapseClick' : 'onExpandClick'], this);
                }
            }
        }
        return this.collapsedEl;
    },

    
    onExpandClick : function(e){
        if(this.isSlid){
            this.panel.expand(false);
        }else{
            this.panel.expand();
        }
    },

    
    onCollapseClick : function(e){
        this.panel.collapse();
    },

    
    beforeCollapse : function(p, animate){
        this.lastAnim = animate;
        if(this.splitEl){
            this.splitEl.hide();
        }
        this.getCollapsedEl().show();
        var el = this.panel.getEl();
        this.originalZIndex = el.getStyle('z-index');
        el.setStyle('z-index', 100);
        this.isCollapsed = true;
        this.layout.layout();
    },

    
    onCollapse : function(animate){
        this.panel.el.setStyle('z-index', 1);
        if(this.lastAnim === false || this.panel.animCollapse === false){
            this.getCollapsedEl().dom.style.visibility = 'visible';
        }else{
            this.getCollapsedEl().slideIn(this.panel.slideAnchor, {duration:.2});
        }
        this.state.collapsed = true;
        this.panel.saveState();
    },

    
    beforeExpand : function(animate){
        if(this.isSlid){
            this.afterSlideIn();
        }
        var c = this.getCollapsedEl();
        this.el.show();
        if(this.position == 'east' || this.position == 'west'){
            this.panel.setSize(undefined, c.getHeight());
        }else{
            this.panel.setSize(c.getWidth(), undefined);
        }
        c.hide();
        c.dom.style.visibility = 'hidden';
        this.panel.el.setStyle('z-index', this.floatingZIndex);
    },

    
    onExpand : function(){
        this.isCollapsed = false;
        if(this.splitEl){
            this.splitEl.show();
        }
        this.layout.layout();
        this.panel.el.setStyle('z-index', this.originalZIndex);
        this.state.collapsed = false;
        this.panel.saveState();
    },

    
    collapseClick : function(e){
        if(this.isSlid){
           e.stopPropagation();
           this.slideIn();
        }else{
           e.stopPropagation();
           this.slideOut();
        }
    },

    
    onHide : function(){
        if(this.isCollapsed){
            this.getCollapsedEl().hide();
        }else if(this.splitEl){
            this.splitEl.hide();
        }
    },

    
    onShow : function(){
        if(this.isCollapsed){
            this.getCollapsedEl().show();
        }else if(this.splitEl){
            this.splitEl.show();
        }
    },

    
    isVisible : function(){
        return !this.panel.hidden;
    },

    
    getMargins : function(){
        return this.isCollapsed && this.cmargins ? this.cmargins : this.margins;
    },

    
    getSize : function(){
        return this.isCollapsed ? this.getCollapsedEl().getSize() : this.panel.getSize();
    },

    
    setPanel : function(panel){
        this.panel = panel;
    },

    
    getMinWidth: function(){
        return this.minWidth;
    },

    
    getMinHeight: function(){
        return this.minHeight;
    },

    
    applyLayoutCollapsed : function(box){
        var ce = this.getCollapsedEl();
        ce.setLeftTop(box.x, box.y);
        ce.setSize(box.width, box.height);
    },

    
    applyLayout : function(box){
        if(this.isCollapsed){
            this.applyLayoutCollapsed(box);
        }else{
            this.panel.setPosition(box.x, box.y);
            this.panel.setSize(box.width, box.height);
        }
    },

    
    beforeSlide: function(){
        this.panel.beforeEffect();
    },

    
    afterSlide : function(){
        this.panel.afterEffect();
    },

    
    initAutoHide : function(){
        if(this.autoHide !== false){
            if(!this.autoHideHd){
                this.autoHideSlideTask = new Ext.util.DelayedTask(this.slideIn, this);
                this.autoHideHd = {
                    "mouseout": function(e){
                        if(!e.within(this.el, true)){
                            this.autoHideSlideTask.delay(500);
                        }
                    },
                    "mouseover" : function(e){
                        this.autoHideSlideTask.cancel();
                    },
                    scope : this
                };
            }
            this.el.on(this.autoHideHd);
            this.collapsedEl.on(this.autoHideHd);
        }
    },

    
    clearAutoHide : function(){
        if(this.autoHide !== false){
            this.el.un("mouseout", this.autoHideHd.mouseout);
            this.el.un("mouseover", this.autoHideHd.mouseover);
            this.collapsedEl.un("mouseout", this.autoHideHd.mouseout);
            this.collapsedEl.un("mouseover", this.autoHideHd.mouseover);
        }
    },

    
    clearMonitor : function(){
        Ext.getDoc().un("click", this.slideInIf, this);
    },

    
    slideOut : function(){
        if(this.isSlid || this.el.hasActiveFx()){
            return;
        }
        this.isSlid = true;
        var ts = this.panel.tools, dh, pc;
        if(ts && ts.toggle){
            ts.toggle.hide();
        }
        this.el.show();

        
        pc = this.panel.collapsed;
        this.panel.collapsed = false;

        if(this.position == 'east' || this.position == 'west'){
            
            dh = this.panel.deferHeight;
            this.panel.deferHeight = false;

            this.panel.setSize(undefined, this.collapsedEl.getHeight());

            
            this.panel.deferHeight = dh;
        }else{
            this.panel.setSize(this.collapsedEl.getWidth(), undefined);
        }

        
        this.panel.collapsed = pc;

        this.restoreLT = [this.el.dom.style.left, this.el.dom.style.top];
        this.el.alignTo(this.collapsedEl, this.getCollapseAnchor());
        this.el.setStyle("z-index", this.floatingZIndex+2);
        this.panel.el.replaceClass('x-panel-collapsed', 'x-panel-floating');
        if(this.animFloat !== false){
            this.beforeSlide();
            this.el.slideIn(this.getSlideAnchor(), {
                callback: function(){
                    this.afterSlide();
                    this.initAutoHide();
                    Ext.getDoc().on("click", this.slideInIf, this);
                },
                scope: this,
                block: true
            });
        }else{
            this.initAutoHide();
             Ext.getDoc().on("click", this.slideInIf, this);
        }
    },

    
    afterSlideIn : function(){
        this.clearAutoHide();
        this.isSlid = false;
        this.clearMonitor();
        this.el.setStyle("z-index", "");
        this.panel.el.replaceClass('x-panel-floating', 'x-panel-collapsed');
        this.el.dom.style.left = this.restoreLT[0];
        this.el.dom.style.top = this.restoreLT[1];

        var ts = this.panel.tools;
        if(ts && ts.toggle){
            ts.toggle.show();
        }
    },

    
    slideIn : function(cb){
        if(!this.isSlid || this.el.hasActiveFx()){
            Ext.callback(cb);
            return;
        }
        this.isSlid = false;
        if(this.animFloat !== false){
            this.beforeSlide();
            this.el.slideOut(this.getSlideAnchor(), {
                callback: function(){
                    this.el.hide();
                    this.afterSlide();
                    this.afterSlideIn();
                    Ext.callback(cb);
                },
                scope: this,
                block: true
            });
        }else{
            this.el.hide();
            this.afterSlideIn();
        }
    },

    
    slideInIf : function(e){
        if(!e.within(this.el)){
            this.slideIn();
        }
    },

    
    anchors : {
        "west" : "left",
        "east" : "right",
        "north" : "top",
        "south" : "bottom"
    },

    
    sanchors : {
        "west" : "l",
        "east" : "r",
        "north" : "t",
        "south" : "b"
    },

    
    canchors : {
        "west" : "tl-tr",
        "east" : "tr-tl",
        "north" : "tl-bl",
        "south" : "bl-tl"
    },

    
    getAnchor : function(){
        return this.anchors[this.position];
    },

    
    getCollapseAnchor : function(){
        return this.canchors[this.position];
    },

    
    getSlideAnchor : function(){
        return this.sanchors[this.position];
    },

    
    getAlignAdj : function(){
        var cm = this.cmargins;
        switch(this.position){
            case "west":
                return [0, 0];
            break;
            case "east":
                return [0, 0];
            break;
            case "north":
                return [0, 0];
            break;
            case "south":
                return [0, 0];
            break;
        }
    },

    
    getExpandAdj : function(){
        var c = this.collapsedEl, cm = this.cmargins;
        switch(this.position){
            case "west":
                return [-(cm.right+c.getWidth()+cm.left), 0];
            break;
            case "east":
                return [cm.right+c.getWidth()+cm.left, 0];
            break;
            case "north":
                return [0, -(cm.top+cm.bottom+c.getHeight())];
            break;
            case "south":
                return [0, cm.top+cm.bottom+c.getHeight()];
            break;
        }
    },

    destroy : function(){
        if (this.autoHideSlideTask && this.autoHideSlideTask.cancel){
            this.autoHideSlideTask.cancel();
        }
        Ext.destroy(this.miniCollapsedEl, this.collapsedEl);
    }
};


Ext.layout.BorderLayout.SplitRegion = function(layout, config, pos){
    Ext.layout.BorderLayout.SplitRegion.superclass.constructor.call(this, layout, config, pos);
    
    this.applyLayout = this.applyFns[pos];
};

Ext.extend(Ext.layout.BorderLayout.SplitRegion, Ext.layout.BorderLayout.Region, {
    
    
    splitTip : "Drag to resize.",
    
    collapsibleSplitTip : "Drag to resize. Double click to hide.",
    
    useSplitTips : false,

    
    splitSettings : {
        north : {
            orientation: Ext.SplitBar.VERTICAL,
            placement: Ext.SplitBar.TOP,
            maxFn : 'getVMaxSize',
            minProp: 'minHeight',
            maxProp: 'maxHeight'
        },
        south : {
            orientation: Ext.SplitBar.VERTICAL,
            placement: Ext.SplitBar.BOTTOM,
            maxFn : 'getVMaxSize',
            minProp: 'minHeight',
            maxProp: 'maxHeight'
        },
        east : {
            orientation: Ext.SplitBar.HORIZONTAL,
            placement: Ext.SplitBar.RIGHT,
            maxFn : 'getHMaxSize',
            minProp: 'minWidth',
            maxProp: 'maxWidth'
        },
        west : {
            orientation: Ext.SplitBar.HORIZONTAL,
            placement: Ext.SplitBar.LEFT,
            maxFn : 'getHMaxSize',
            minProp: 'minWidth',
            maxProp: 'maxWidth'
        }
    },

    
    applyFns : {
        west : function(box){
            if(this.isCollapsed){
                return this.applyLayoutCollapsed(box);
            }
            var sd = this.splitEl.dom, s = sd.style;
            this.panel.setPosition(box.x, box.y);
            var sw = sd.offsetWidth;
            s.left = (box.x+box.width-sw)+'px';
            s.top = (box.y)+'px';
            s.height = Math.max(0, box.height)+'px';
            this.panel.setSize(box.width-sw, box.height);
        },
        east : function(box){
            if(this.isCollapsed){
                return this.applyLayoutCollapsed(box);
            }
            var sd = this.splitEl.dom, s = sd.style;
            var sw = sd.offsetWidth;
            this.panel.setPosition(box.x+sw, box.y);
            s.left = (box.x)+'px';
            s.top = (box.y)+'px';
            s.height = Math.max(0, box.height)+'px';
            this.panel.setSize(box.width-sw, box.height);
        },
        north : function(box){
            if(this.isCollapsed){
                return this.applyLayoutCollapsed(box);
            }
            var sd = this.splitEl.dom, s = sd.style;
            var sh = sd.offsetHeight;
            this.panel.setPosition(box.x, box.y);
            s.left = (box.x)+'px';
            s.top = (box.y+box.height-sh)+'px';
            s.width = Math.max(0, box.width)+'px';
            this.panel.setSize(box.width, box.height-sh);
        },
        south : function(box){
            if(this.isCollapsed){
                return this.applyLayoutCollapsed(box);
            }
            var sd = this.splitEl.dom, s = sd.style;
            var sh = sd.offsetHeight;
            this.panel.setPosition(box.x, box.y+sh);
            s.left = (box.x)+'px';
            s.top = (box.y)+'px';
            s.width = Math.max(0, box.width)+'px';
            this.panel.setSize(box.width, box.height-sh);
        }
    },

    
    render : function(ct, p){
        Ext.layout.BorderLayout.SplitRegion.superclass.render.call(this, ct, p);

        var ps = this.position;

        this.splitEl = ct.createChild({
            cls: "x-layout-split x-layout-split-"+ps, html: "&#160;",
            id: this.panel.id + '-xsplit'
        });

        if(this.collapseMode == 'mini'){
            this.miniSplitEl = this.splitEl.createChild({
                cls: "x-layout-mini x-layout-mini-"+ps, html: "&#160;"
            });
            this.miniSplitEl.addClassOnOver('x-layout-mini-over');
            this.miniSplitEl.on('click', this.onCollapseClick, this, {stopEvent:true});
        }

        var s = this.splitSettings[ps];

        this.split = new Ext.SplitBar(this.splitEl.dom, p.el, s.orientation);
        this.split.tickSize = this.tickSize;
        this.split.placement = s.placement;
        this.split.getMaximumSize = this[s.maxFn].createDelegate(this);
        this.split.minSize = this.minSize || this[s.minProp];
        this.split.on("beforeapply", this.onSplitMove, this);
        this.split.useShim = this.useShim === true;
        this.maxSize = this.maxSize || this[s.maxProp];

        if(p.hidden){
            this.splitEl.hide();
        }

        if(this.useSplitTips){
            this.splitEl.dom.title = this.collapsible ? this.collapsibleSplitTip : this.splitTip;
        }
        if(this.collapsible){
            this.splitEl.on("dblclick", this.onCollapseClick,  this);
        }
    },

    
    getSize : function(){
        if(this.isCollapsed){
            return this.collapsedEl.getSize();
        }
        var s = this.panel.getSize();
        if(this.position == 'north' || this.position == 'south'){
            s.height += this.splitEl.dom.offsetHeight;
        }else{
            s.width += this.splitEl.dom.offsetWidth;
        }
        return s;
    },

    
    getHMaxSize : function(){
         var cmax = this.maxSize || 10000;
         var center = this.layout.center;
         return Math.min(cmax, (this.el.getWidth()+center.el.getWidth())-center.getMinWidth());
    },

    
    getVMaxSize : function(){
        var cmax = this.maxSize || 10000;
        var center = this.layout.center;
        return Math.min(cmax, (this.el.getHeight()+center.el.getHeight())-center.getMinHeight());
    },

    
    onSplitMove : function(split, newSize){
        var s = this.panel.getSize();
        this.lastSplitSize = newSize;
        if(this.position == 'north' || this.position == 'south'){
            this.panel.setSize(s.width, newSize);
            this.state.height = newSize;
        }else{
            this.panel.setSize(newSize, s.height);
            this.state.width = newSize;
        }
        this.layout.layout();
        this.panel.saveState();
        return false;
    },

    
    getSplitBar : function(){
        return this.split;
    },

    
    destroy : function() {
        Ext.destroy(this.miniSplitEl, this.split, this.splitEl);
        Ext.layout.BorderLayout.SplitRegion.superclass.destroy.call(this);
    }
});

Ext.Container.LAYOUTS['border'] = Ext.layout.BorderLayout;
Ext.layout.FormLayout = Ext.extend(Ext.layout.AnchorLayout, {

    
    labelSeparator : ':',

    

    
    trackLabels: false,

    type: 'form',

    onRemove: function(c){
        Ext.layout.FormLayout.superclass.onRemove.call(this, c);
        if(this.trackLabels){
            c.un('show', this.onFieldShow, this);
            c.un('hide', this.onFieldHide, this);
        }
        
        var el = c.getPositionEl(),
            ct = c.getItemCt && c.getItemCt();
        if (c.rendered && ct) {
            if (el && el.dom) {
                el.insertAfter(ct);
            }
            Ext.destroy(ct);
            Ext.destroyMembers(c, 'label', 'itemCt');
            if (c.customItemCt) {
                Ext.destroyMembers(c, 'getItemCt', 'customItemCt');
            }
        }
    },

    
    setContainer : function(ct){
        Ext.layout.FormLayout.superclass.setContainer.call(this, ct);
        if(ct.labelAlign){
            ct.addClass('x-form-label-'+ct.labelAlign);
        }

        if(ct.hideLabels){
            Ext.apply(this, {
                labelStyle: 'display:none',
                elementStyle: 'padding-left:0;',
                labelAdjust: 0
            });
        }else{
            this.labelSeparator = ct.labelSeparator || this.labelSeparator;
            ct.labelWidth = ct.labelWidth || 100;
            if(Ext.isNumber(ct.labelWidth)){
                var pad = Ext.isNumber(ct.labelPad) ? ct.labelPad : 5;
                Ext.apply(this, {
                    labelAdjust: ct.labelWidth + pad,
                    labelStyle: 'width:' + ct.labelWidth + 'px;',
                    elementStyle: 'padding-left:' + (ct.labelWidth + pad) + 'px'
                });
            }
            if(ct.labelAlign == 'top'){
                Ext.apply(this, {
                    labelStyle: 'width:auto;',
                    labelAdjust: 0,
                    elementStyle: 'padding-left:0;'
                });
            }
        }
    },

    
    isHide: function(c){
        return c.hideLabel || this.container.hideLabels;
    },

    onFieldShow: function(c){
        c.getItemCt().removeClass('x-hide-' + c.hideMode);

        
        if (c.isComposite) {
            c.doLayout();
        }
    },

    onFieldHide: function(c){
        c.getItemCt().addClass('x-hide-' + c.hideMode);
    },

    
    getLabelStyle: function(s){
        var ls = '', items = [this.labelStyle, s];
        for (var i = 0, len = items.length; i < len; ++i){
            if (items[i]){
                ls += items[i];
                if (ls.substr(-1, 1) != ';'){
                    ls += ';';
                }
            }
        }
        return ls;
    },

    

    
    renderItem : function(c, position, target){
        if(c && (c.isFormField || c.fieldLabel) && c.inputType != 'hidden'){
            var args = this.getTemplateArgs(c);
            if(Ext.isNumber(position)){
                position = target.dom.childNodes[position] || null;
            }
            if(position){
                c.itemCt = this.fieldTpl.insertBefore(position, args, true);
            }else{
                c.itemCt = this.fieldTpl.append(target, args, true);
            }
            if(!c.getItemCt){
                
                
                Ext.apply(c, {
                    getItemCt: function(){
                        return c.itemCt;
                    },
                    customItemCt: true
                });
            }
            c.label = c.getItemCt().child('label.x-form-item-label');
            if(!c.rendered){
                c.render('x-form-el-' + c.id);
            }else if(!this.isValidParent(c, target)){
                Ext.fly('x-form-el-' + c.id).appendChild(c.getPositionEl());
            }
            if(this.trackLabels){
                if(c.hidden){
                    this.onFieldHide(c);
                }
                c.on({
                    scope: this,
                    show: this.onFieldShow,
                    hide: this.onFieldHide
                });
            }
            this.configureItem(c);
        }else {
            Ext.layout.FormLayout.superclass.renderItem.apply(this, arguments);
        }
    },

    
    getTemplateArgs: function(field) {
        var noLabelSep = !field.fieldLabel || field.hideLabel;

        return {
            id            : field.id,
            label         : field.fieldLabel,
            itemCls       : (field.itemCls || this.container.itemCls || '') + (field.hideLabel ? ' x-hide-label' : ''),
            clearCls      : field.clearCls || 'x-form-clear-left',
            labelStyle    : this.getLabelStyle(field.labelStyle),
            elementStyle  : this.elementStyle || '',
            labelSeparator: noLabelSep ? '' : (Ext.isDefined(field.labelSeparator) ? field.labelSeparator : this.labelSeparator)
        };
    },

    
    adjustWidthAnchor: function(value, c){
        if(c.label && !this.isHide(c) && (this.container.labelAlign != 'top')){
            var adjust = Ext.isIE6 || (Ext.isIE && !Ext.isStrict);
            return value - this.labelAdjust + (adjust ? -3 : 0);
        }
        return value;
    },

    adjustHeightAnchor : function(value, c){
        if(c.label && !this.isHide(c) && (this.container.labelAlign == 'top')){
            return value - c.label.getHeight();
        }
        return value;
    },

    
    isValidParent : function(c, target){
        return target && this.container.getEl().contains(c.getPositionEl());
    }

    
});

Ext.Container.LAYOUTS['form'] = Ext.layout.FormLayout;

Ext.layout.AccordionLayout = Ext.extend(Ext.layout.FitLayout, {
    
    fill : true,
    
    autoWidth : true,
    
    titleCollapse : true,
    
    hideCollapseTool : false,
    
    collapseFirst : false,
    
    animate : false,
    
    sequence : false,
    
    activeOnTop : false,

    type: 'accordion',

    renderItem : function(c){
        if(this.animate === false){
            c.animCollapse = false;
        }
        c.collapsible = true;
        if(this.autoWidth){
            c.autoWidth = true;
        }
        if(this.titleCollapse){
            c.titleCollapse = true;
        }
        if(this.hideCollapseTool){
            c.hideCollapseTool = true;
        }
        if(this.collapseFirst !== undefined){
            c.collapseFirst = this.collapseFirst;
        }
        if(!this.activeItem && !c.collapsed){
            this.setActiveItem(c, true);
        }else if(this.activeItem && this.activeItem != c){
            c.collapsed = true;
        }
        Ext.layout.AccordionLayout.superclass.renderItem.apply(this, arguments);
        c.header.addClass('x-accordion-hd');
        c.on('beforeexpand', this.beforeExpand, this);
    },

    onRemove: function(c){
        Ext.layout.AccordionLayout.superclass.onRemove.call(this, c);
        if(c.rendered){
            c.header.removeClass('x-accordion-hd');
        }
        c.un('beforeexpand', this.beforeExpand, this);
    },

    
    beforeExpand : function(p, anim){
        var ai = this.activeItem;
        if(ai){
            if(this.sequence){
                delete this.activeItem;
                if (!ai.collapsed){
                    ai.collapse({callback:function(){
                        p.expand(anim || true);
                    }, scope: this});
                    return false;
                }
            }else{
                ai.collapse(this.animate);
            }
        }
        this.setActive(p);
        if(this.activeOnTop){
            p.el.dom.parentNode.insertBefore(p.el.dom, p.el.dom.parentNode.firstChild);
        }
        
        this.layout();
    },

    
    setItemSize : function(item, size){
        if(this.fill && item){
            var hh = 0, i, ct = this.getRenderedItems(this.container), len = ct.length, p;
            
            for (i = 0; i < len; i++) {
                if((p = ct[i]) != item && !p.hidden){
                    hh += p.header.getHeight();
                }
            };
            
            size.height -= hh;
            
            
            item.setSize(size);
        }
    },

    
    setActiveItem : function(item){
        this.setActive(item, true);
    },

    
    setActive : function(item, expand){
        var ai = this.activeItem;
        item = this.container.getComponent(item);
        if(ai != item){
            if(item.rendered && item.collapsed && expand){
                item.expand();
            }else{
                if(ai){
                   ai.fireEvent('deactivate', ai);
                }
                this.activeItem = item;
                item.fireEvent('activate', item);
            }
        }
    }
});
Ext.Container.LAYOUTS.accordion = Ext.layout.AccordionLayout;


Ext.layout.Accordion = Ext.layout.AccordionLayout;
Ext.layout.TableLayout = Ext.extend(Ext.layout.ContainerLayout, {
    

    
    monitorResize:false,

    type: 'table',

    targetCls: 'x-table-layout-ct',

    
    tableAttrs:null,

    
    setContainer : function(ct){
        Ext.layout.TableLayout.superclass.setContainer.call(this, ct);

        this.currentRow = 0;
        this.currentColumn = 0;
        this.cells = [];
    },
    
    
    onLayout : function(ct, target){
        var cs = ct.items.items, len = cs.length, c, i;

        if(!this.table){
            target.addClass('x-table-layout-ct');

            this.table = target.createChild(
                Ext.apply({tag:'table', cls:'x-table-layout', cellspacing: 0, cn: {tag: 'tbody'}}, this.tableAttrs), null, true);
        }
        this.renderAll(ct, target);
    },

    
    getRow : function(index){
        var row = this.table.tBodies[0].childNodes[index];
        if(!row){
            row = document.createElement('tr');
            this.table.tBodies[0].appendChild(row);
        }
        return row;
    },

    
    getNextCell : function(c){
        var cell = this.getNextNonSpan(this.currentColumn, this.currentRow);
        var curCol = this.currentColumn = cell[0], curRow = this.currentRow = cell[1];
        for(var rowIndex = curRow; rowIndex < curRow + (c.rowspan || 1); rowIndex++){
            if(!this.cells[rowIndex]){
                this.cells[rowIndex] = [];
            }
            for(var colIndex = curCol; colIndex < curCol + (c.colspan || 1); colIndex++){
                this.cells[rowIndex][colIndex] = true;
            }
        }
        var td = document.createElement('td');
        if(c.cellId){
            td.id = c.cellId;
        }
        var cls = 'x-table-layout-cell';
        if(c.cellCls){
            cls += ' ' + c.cellCls;
        }
        td.className = cls;
        if(c.colspan){
            td.colSpan = c.colspan;
        }
        if(c.rowspan){
            td.rowSpan = c.rowspan;
        }
        this.getRow(curRow).appendChild(td);
        return td;
    },

    
    getNextNonSpan: function(colIndex, rowIndex){
        var cols = this.columns;
        while((cols && colIndex >= cols) || (this.cells[rowIndex] && this.cells[rowIndex][colIndex])) {
            if(cols && colIndex >= cols){
                rowIndex++;
                colIndex = 0;
            }else{
                colIndex++;
            }
        }
        return [colIndex, rowIndex];
    },

    
    renderItem : function(c, position, target){
        
        if(!this.table){
            this.table = target.createChild(
                Ext.apply({tag:'table', cls:'x-table-layout', cellspacing: 0, cn: {tag: 'tbody'}}, this.tableAttrs), null, true);
        }
        if(c && !c.rendered){
            c.render(this.getNextCell(c));
            this.configureItem(c, position);
        }else if(c && !this.isValidParent(c, target)){
            var container = this.getNextCell(c);
            container.insertBefore(c.getPositionEl().dom, null);
            c.container = Ext.get(container);
            this.configureItem(c, position);
        }
    },

    
    isValidParent : function(c, target){
        return c.getPositionEl().up('table', 5).dom.parentNode === (target.dom || target);
    }

    
});

Ext.Container.LAYOUTS['table'] = Ext.layout.TableLayout;
Ext.layout.AbsoluteLayout = Ext.extend(Ext.layout.AnchorLayout, {

    extraCls: 'x-abs-layout-item',

    type: 'absolute',

    onLayout : function(ct, target){
        target.position();
        this.paddingLeft = target.getPadding('l');
        this.paddingTop = target.getPadding('t');
        Ext.layout.AbsoluteLayout.superclass.onLayout.call(this, ct, target);
    },

    
    adjustWidthAnchor : function(value, comp){
        return value ? value - comp.getPosition(true)[0] + this.paddingLeft : value;
    },

    
    adjustHeightAnchor : function(value, comp){
        return  value ? value - comp.getPosition(true)[1] + this.paddingTop : value;
    }
    
});
Ext.Container.LAYOUTS['absolute'] = Ext.layout.AbsoluteLayout;

Ext.layout.BoxLayout = Ext.extend(Ext.layout.ContainerLayout, {
    
    defaultMargins : {left:0,top:0,right:0,bottom:0},
    
    padding : '0',
    
    pack : 'start',

    
    monitorResize : true,
    type: 'box',
    scrollOffset : 0,
    extraCls : 'x-box-item',
    targetCls : 'x-box-layout-ct',
    innerCls : 'x-box-inner',

    constructor : function(config){
        Ext.layout.BoxLayout.superclass.constructor.call(this, config);

        if (Ext.isString(this.defaultMargins)) {
            this.defaultMargins = this.parseMargins(this.defaultMargins);
        }
    },

    
    onLayout: function(container, target) {
        Ext.layout.BoxLayout.superclass.onLayout.call(this, container, target);

        var items = this.getVisibleItems(container),
            tSize = this.getLayoutTargetSize();

        
        this.layoutTargetLastSize = tSize;

        
        this.childBoxCache = this.calculateChildBoxes(items, tSize);

        this.updateInnerCtSize(tSize, this.childBoxCache);
        this.updateChildBoxes(this.childBoxCache.boxes);

        
        this.handleTargetOverflow(tSize, container, target);
    },

    
    updateChildBoxes: function(boxes) {
        for (var i = 0, length = boxes.length; i < length; i++) {
            var box  = boxes[i],
                comp = box.component;

            if (box.dirtySize) {
                comp.setSize(box.width, box.height);
            }
            
            if (isNaN(box.left) || isNaN(box.top)) {
                continue;
            }
            comp.setPosition(box.left, box.top);
        }
    },

    
    updateInnerCtSize: Ext.emptyFn,

    
    handleTargetOverflow: function(previousTargetSize, container, target) {
        var overflow = target.getStyle('overflow');

        if (overflow && overflow != 'hidden' &&!this.adjustmentPass) {
            var newTargetSize = this.getLayoutTargetSize();
            if (newTargetSize.width != previousTargetSize.width || newTargetSize.height != previousTargetSize.height){
                this.adjustmentPass = true;
                this.onLayout(container, target);
            }
        }

        delete this.adjustmentPass;
    },

    
    isValidParent : function(c, target){
        return this.innerCt && c.getPositionEl().dom.parentNode == this.innerCt.dom;
    },

    
    getVisibleItems: function(ct) {
        var ct  = ct || this.container,
            t   = ct.getLayoutTarget(),
            cti = ct.items.items,
            len = cti.length,

            i, c, items = [];

        for (i = 0; i < len; i++) {
            if((c = cti[i]).rendered && this.isValidParent(c, t) && c.hidden !== true  && c.collapsed !== true){
                items.push(c);
            }
        }

        return items;
    },

    
    renderAll : function(ct, target){
        if(!this.innerCt){
            
            
            this.innerCt = target.createChild({cls:this.innerCls});
            this.padding = this.parseMargins(this.padding);
        }
        Ext.layout.BoxLayout.superclass.renderAll.call(this, ct, this.innerCt);
    },

    getLayoutTargetSize : function(){
        var target = this.container.getLayoutTarget(), ret;
        if (target) {
            ret = target.getViewSize();

            
            
            
            if (Ext.isIE && Ext.isStrict && ret.width == 0){
                ret =  target.getStyleSize();
            }

            ret.width -= target.getPadding('lr');
            ret.height -= target.getPadding('tb');
        }
        return ret;
    },

    
    renderItem : function(c){
        if(Ext.isString(c.margins)){
            c.margins = this.parseMargins(c.margins);
        }else if(!c.margins){
            c.margins = this.defaultMargins;
        }
        Ext.layout.BoxLayout.superclass.renderItem.apply(this, arguments);
    }
});


Ext.layout.VBoxLayout = Ext.extend(Ext.layout.BoxLayout, {
    
    align : 'left', 
    type: 'vbox',

    

    

    
    updateInnerCtSize: function(tSize, calcs) {
        var innerCtHeight = tSize.height,
            innerCtWidth  = calcs.meta.maxWidth + this.padding.left + this.padding.right;

        if (this.align == 'stretch') {
            innerCtWidth = tSize.width;
        } else if (this.align == 'center') {
            innerCtWidth = Math.max(tSize.width, innerCtWidth);
        }

        
        
        this.innerCt.setSize(innerCtWidth || undefined, innerCtHeight || undefined);
    },

    
    calculateChildBoxes: function(visibleItems, targetSize) {
        var visibleCount = visibleItems.length,

            padding      = this.padding,
            topOffset    = padding.top,
            leftOffset   = padding.left,
            paddingVert  = topOffset  + padding.bottom,
            paddingHoriz = leftOffset + padding.right,

            width        = targetSize.width - this.scrollOffset,
            height       = targetSize.height,
            availWidth   = Math.max(0, width - paddingHoriz),

            isStart      = this.pack == 'start',
            isCenter     = this.pack == 'center',
            isEnd        = this.pack == 'end',

            nonFlexHeight= 0,
            maxWidth     = 0,
            totalFlex    = 0,

            
            boxes        = [],

            
            child, childWidth, childHeight, childSize, childMargins, canLayout, i, calcs, flexedHeight, horizMargins, stretchWidth;

            
            for (i = 0; i < visibleCount; i++) {
                child = visibleItems[i];
                childHeight = child.height;
                childWidth  = child.width;
                canLayout   = !child.hasLayout && Ext.isFunction(child.doLayout);


                
                if (!Ext.isNumber(childHeight)) {

                    
                    if (child.flex && !childHeight) {
                        totalFlex += child.flex;

                    
                    } else {
                        
                        
                        if (!childHeight && canLayout) {
                            child.doLayout();
                        }

                        childSize = child.getSize();
                        childWidth = childSize.width;
                        childHeight = childSize.height;
                    }
                }

                childMargins = child.margins;

                nonFlexHeight += (childHeight || 0) + childMargins.top + childMargins.bottom;

                
                if (!Ext.isNumber(childWidth)) {
                    if (canLayout) {
                        child.doLayout();
                    }
                    childWidth = child.getWidth();
                }

                maxWidth = Math.max(maxWidth, childWidth + childMargins.left + childMargins.right);

                
                boxes.push({
                    component: child,
                    height   : childHeight || undefined,
                    width    : childWidth || undefined
                });
            }

            
            var availableHeight = Math.max(0, (height - nonFlexHeight - paddingVert));

            if (isCenter) {
                topOffset += availableHeight / 2;
            } else if (isEnd) {
                topOffset += availableHeight;
            }

            
            var remainingHeight = availableHeight,
                remainingFlex   = totalFlex;

            
            for (i = 0; i < visibleCount; i++) {
                child = visibleItems[i];
                calcs = boxes[i];

                childMargins = child.margins;
                horizMargins = childMargins.left + childMargins.right;

                topOffset   += childMargins.top;

                if (isStart && child.flex && !child.height) {
                    flexedHeight     = Math.ceil((child.flex / remainingFlex) * remainingHeight);
                    remainingHeight -= flexedHeight;
                    remainingFlex   -= child.flex;

                    calcs.height = flexedHeight;
                    calcs.dirtySize = true;
                }

                calcs.left = leftOffset + childMargins.left;
                calcs.top  = topOffset;

                switch (this.align) {
                    case 'stretch':
                        stretchWidth = availWidth - horizMargins;
                        calcs.width  = stretchWidth.constrain(child.minWidth || 0, child.maxWidth || 1000000);
                        calcs.dirtySize = true;
                        break;
                    case 'stretchmax':
                        stretchWidth = maxWidth - horizMargins;
                        calcs.width  = stretchWidth.constrain(child.minWidth || 0, child.maxWidth || 1000000);
                        calcs.dirtySize = true;
                        break;
                    case 'center':
                        var diff = availWidth - calcs.width - horizMargins;
                        if (diff > 0) {
                            calcs.left = leftOffset + horizMargins + (diff / 2);
                        }
                }

                topOffset += calcs.height + childMargins.bottom;
            }

        return {
            boxes: boxes,
            meta : {
                maxWidth: maxWidth
            }
        };
    }
});

Ext.Container.LAYOUTS.vbox = Ext.layout.VBoxLayout;


Ext.layout.HBoxLayout = Ext.extend(Ext.layout.BoxLayout, {
    
    align: 'top', 

    type : 'hbox',

    
    updateInnerCtSize: function(tSize, calcs) {
        var innerCtWidth  = tSize.width,
            innerCtHeight = calcs.meta.maxHeight + this.padding.top + this.padding.bottom;

        if (this.align == 'stretch') {
            innerCtHeight = tSize.height;
        } else if (this.align == 'middle') {
            innerCtHeight = Math.max(tSize.height, innerCtHeight);
        }

        this.innerCt.setSize(innerCtWidth || undefined, innerCtHeight || undefined);
    },

    
    

    
    calculateChildBoxes: function(visibleItems, targetSize) {
        var visibleCount = visibleItems.length,

            padding      = this.padding,
            topOffset    = padding.top,
            leftOffset   = padding.left,
            paddingVert  = topOffset  + padding.bottom,
            paddingHoriz = leftOffset + padding.right,

            width        = targetSize.width - this.scrollOffset,
            height       = targetSize.height,
            availHeight  = Math.max(0, height - paddingVert),

            isStart      = this.pack == 'start',
            isCenter     = this.pack == 'center',
            isEnd        = this.pack == 'end',
            

            nonFlexWidth = 0,
            maxHeight    = 0,
            totalFlex    = 0,

            
            boxes        = [],

            
            child, childWidth, childHeight, childSize, childMargins, canLayout, i, calcs, flexedWidth, vertMargins, stretchHeight;

            
            for (i = 0; i < visibleCount; i++) {
                child       = visibleItems[i];
                childHeight = child.height;
                childWidth  = child.width;
                canLayout   = !child.hasLayout && Ext.isFunction(child.doLayout);

                
                if (!Ext.isNumber(childWidth)) {

                    
                    if (child.flex && !childWidth) {
                        totalFlex += child.flex;

                    
                    } else {
                        
                        
                        if (!childWidth && canLayout) {
                            child.doLayout();
                        }

                        childSize   = child.getSize();
                        childWidth  = childSize.width;
                        childHeight = childSize.height;
                    }
                }

                childMargins = child.margins;

                nonFlexWidth += (childWidth || 0) + childMargins.left + childMargins.right;

                
                if (!Ext.isNumber(childHeight)) {
                    if (canLayout) {
                        child.doLayout();
                    }
                    childHeight = child.getHeight();
                }

                maxHeight = Math.max(maxHeight, childHeight + childMargins.top + childMargins.bottom);

                
                boxes.push({
                    component: child,
                    height   : childHeight || undefined,
                    width    : childWidth || undefined
                });
            }

            
            var availableWidth = Math.max(0, (width - nonFlexWidth - paddingHoriz));

            if (isCenter) {
                leftOffset += availableWidth / 2;
            } else if (isEnd) {
                leftOffset += availableWidth;
            }

            
            var remainingWidth = availableWidth,
                remainingFlex  = totalFlex;

            
            for (i = 0; i < visibleCount; i++) {
                child = visibleItems[i];
                calcs = boxes[i];

                childMargins = child.margins;
                vertMargins  = childMargins.top + childMargins.bottom;

                leftOffset  += childMargins.left;

                if (isStart && child.flex && !child.width) {
                    flexedWidth     = Math.ceil((child.flex / remainingFlex) * remainingWidth);
                    remainingWidth -= flexedWidth;
                    remainingFlex  -= child.flex;

                    calcs.width = flexedWidth;
                    calcs.dirtySize = true;
                }

                calcs.left = leftOffset;
                calcs.top  = topOffset + childMargins.top;

                switch (this.align) {
                    case 'stretch':
                        stretchHeight = availHeight - vertMargins;
                        calcs.height  = stretchHeight.constrain(child.minHeight || 0, child.maxHeight || 1000000);
                        calcs.dirtySize = true;
                        break;
                    case 'stretchmax':
                        stretchHeight = maxHeight - vertMargins;
                        calcs.height  = stretchHeight.constrain(child.minHeight || 0, child.maxHeight || 1000000);
                        calcs.dirtySize = true;
                        break;
                    case 'middle':
                        var diff = availHeight - calcs.height - vertMargins;
                        if (diff > 0) {
                            calcs.top = topOffset + vertMargins + (diff / 2);
                        }
                }
                leftOffset += calcs.width + childMargins.right;
            }

        return {
            boxes: boxes,
            meta : {
                maxHeight: maxHeight
            }
        };
    }
});

Ext.Container.LAYOUTS.hbox = Ext.layout.HBoxLayout;

Ext.layout.ToolbarLayout = Ext.extend(Ext.layout.ContainerLayout, {
    monitorResize : true,

    type: 'toolbar',

    
    triggerWidth: 18,

    
    noItemsMenuText : '<div class="x-toolbar-no-items">(None)</div>',

    
    lastOverflow: false,

    
    tableHTML: [
        '<table cellspacing="0" class="x-toolbar-ct">',
            '<tbody>',
                '<tr>',
                    '<td class="x-toolbar-left" align="{0}">',
                        '<table cellspacing="0">',
                            '<tbody>',
                                '<tr class="x-toolbar-left-row"></tr>',
                            '</tbody>',
                        '</table>',
                    '</td>',
                    '<td class="x-toolbar-right" align="right">',
                        '<table cellspacing="0" class="x-toolbar-right-ct">',
                            '<tbody>',
                                '<tr>',
                                    '<td>',
                                        '<table cellspacing="0">',
                                            '<tbody>',
                                                '<tr class="x-toolbar-right-row"></tr>',
                                            '</tbody>',
                                        '</table>',
                                    '</td>',
                                    '<td>',
                                        '<table cellspacing="0">',
                                            '<tbody>',
                                                '<tr class="x-toolbar-extras-row"></tr>',
                                            '</tbody>',
                                        '</table>',
                                    '</td>',
                                '</tr>',
                            '</tbody>',
                        '</table>',
                    '</td>',
                '</tr>',
            '</tbody>',
        '</table>'
    ].join(""),

    
    onLayout : function(ct, target) {
        
        if (!this.leftTr) {
            var align = ct.buttonAlign == 'center' ? 'center' : 'left';

            target.addClass('x-toolbar-layout-ct');
            target.insertHtml('beforeEnd', String.format(this.tableHTML, align));

            this.leftTr   = target.child('tr.x-toolbar-left-row', true);
            this.rightTr  = target.child('tr.x-toolbar-right-row', true);
            this.extrasTr = target.child('tr.x-toolbar-extras-row', true);

            if (this.hiddenItem == undefined) {
                
                this.hiddenItems = [];
            }
        }

        var side     = ct.buttonAlign == 'right' ? this.rightTr : this.leftTr,
            items    = ct.items.items,
            position = 0;

        
        for (var i = 0, len = items.length, c; i < len; i++, position++) {
            c = items[i];

            if (c.isFill) {
                side   = this.rightTr;
                position = -1;
            } else if (!c.rendered) {
                c.render(this.insertCell(c, side, position));
            } else {
                if (!c.xtbHidden && !this.isValidParent(c, side.childNodes[position])) {
                    var td = this.insertCell(c, side, position);
                    td.appendChild(c.getPositionEl().dom);
                    c.container = Ext.get(td);
                }
            }
        }

        
        this.cleanup(this.leftTr);
        this.cleanup(this.rightTr);
        this.cleanup(this.extrasTr);
        this.fitToSize(target);
    },

    
    cleanup : function(el) {
        var cn = el.childNodes, i, c;

        for (i = cn.length-1; i >= 0 && (c = cn[i]); i--) {
            if (!c.firstChild) {
                el.removeChild(c);
            }
        }
    },

    
    insertCell : function(c, target, position) {
        var td = document.createElement('td');
        td.className = 'x-toolbar-cell';

        target.insertBefore(td, target.childNodes[position] || null);

        return td;
    },

    
    hideItem : function(item) {
        this.hiddenItems.push(item);

        item.xtbHidden = true;
        item.xtbWidth = item.getPositionEl().dom.parentNode.offsetWidth;
        item.hide();
    },

    
    unhideItem : function(item) {
        item.show();
        item.xtbHidden = false;
        this.hiddenItems.remove(item);
    },

    
    getItemWidth : function(c) {
        return c.hidden ? (c.xtbWidth || 0) : c.getPositionEl().dom.parentNode.offsetWidth;
    },

    
    fitToSize : function(target) {
        if (this.container.enableOverflow === false) {
            return;
        }

        var width       = target.dom.clientWidth,
            tableWidth  = target.dom.firstChild.offsetWidth,
            clipWidth   = width - this.triggerWidth,
            lastWidth   = this.lastWidth || 0,

            hiddenItems = this.hiddenItems,
            hasHiddens  = hiddenItems.length != 0,
            isLarger    = width >= lastWidth;

        this.lastWidth  = width;

        if (tableWidth > width || (hasHiddens && isLarger)) {
            var items     = this.container.items.items,
                len       = items.length,
                loopWidth = 0,
                item;

            for (var i = 0; i < len; i++) {
                item = items[i];

                if (!item.isFill) {
                    loopWidth += this.getItemWidth(item);
                    if (loopWidth > clipWidth) {
                        if (!(item.hidden || item.xtbHidden)) {
                            this.hideItem(item);
                        }
                    } else if (item.xtbHidden) {
                        this.unhideItem(item);
                    }
                }
            }
        }

        
        hasHiddens = hiddenItems.length != 0;

        if (hasHiddens) {
            this.initMore();

            if (!this.lastOverflow) {
                this.container.fireEvent('overflowchange', this.container, true);
                this.lastOverflow = true;
            }
        } else if (this.more) {
            this.clearMenu();
            this.more.destroy();
            delete this.more;

            if (this.lastOverflow) {
                this.container.fireEvent('overflowchange', this.container, false);
                this.lastOverflow = false;
            }
        }
    },

    
    createMenuConfig : function(component, hideOnClick){
        var config = Ext.apply({}, component.initialConfig),
            group  = component.toggleGroup;

        Ext.copyTo(config, component, [
            'iconCls', 'icon', 'itemId', 'disabled', 'handler', 'scope', 'menu'
        ]);

        Ext.apply(config, {
            text       : component.overflowText || component.text,
            hideOnClick: hideOnClick
        });

        if (group || component.enableToggle) {
            Ext.apply(config, {
                group  : group,
                checked: component.pressed,
                listeners: {
                    checkchange: function(item, checked){
                        component.toggle(checked);
                    }
                }
            });
        }

        delete config.ownerCt;
        delete config.xtype;
        delete config.id;

        return config;
    },

    
    addComponentToMenu : function(menu, component) {
        if (component instanceof Ext.Toolbar.Separator) {
            menu.add('-');

        } else if (Ext.isFunction(component.isXType)) {
            if (component.isXType('splitbutton')) {
                menu.add(this.createMenuConfig(component, true));

            } else if (component.isXType('button')) {
                menu.add(this.createMenuConfig(component, !component.menu));

            } else if (component.isXType('buttongroup')) {
                component.items.each(function(item){
                     this.addComponentToMenu(menu, item);
                }, this);
            }
        }
    },

    
    clearMenu : function(){
        var menu = this.moreMenu;
        if (menu && menu.items) {
            menu.items.each(function(item){
                delete item.menu;
            });
        }
    },

    
    beforeMoreShow : function(menu) {
        var items = this.container.items.items,
            len   = items.length,
            item,
            prev;

        var needsSep = function(group, item){
            return group.isXType('buttongroup') && !(item instanceof Ext.Toolbar.Separator);
        };

        this.clearMenu();
        menu.removeAll();
        for (var i = 0; i < len; i++) {
            item = items[i];
            if (item.xtbHidden) {
                if (prev && (needsSep(item, prev) || needsSep(prev, item))) {
                    menu.add('-');
                }
                this.addComponentToMenu(menu, item);
                prev = item;
            }
        }

        
        if (menu.items.length < 1) {
            menu.add(this.noItemsMenuText);
        }
    },

    
    initMore : function(){
        if (!this.more) {
            
            this.moreMenu = new Ext.menu.Menu({
                ownerCt : this.container,
                listeners: {
                    beforeshow: this.beforeMoreShow,
                    scope: this
                }
            });

            
            this.more = new Ext.Button({
                iconCls: 'x-toolbar-more-icon',
                cls    : 'x-toolbar-more',
                menu   : this.moreMenu,
                ownerCt: this.container
            });

            var td = this.insertCell(this.more, this.extrasTr, 100);
            this.more.render(td);
        }
    },

    destroy : function(){
        Ext.destroy(this.more, this.moreMenu);
        delete this.leftTr;
        delete this.rightTr;
        delete this.extrasTr;
        Ext.layout.ToolbarLayout.superclass.destroy.call(this);
    }
});

Ext.Container.LAYOUTS.toolbar = Ext.layout.ToolbarLayout;

 Ext.layout.MenuLayout = Ext.extend(Ext.layout.ContainerLayout, {
    monitorResize : true,

    type: 'menu',

    setContainer : function(ct){
        this.monitorResize = !ct.floating;
        
        
        ct.on('autosize', this.doAutoSize, this);
        Ext.layout.MenuLayout.superclass.setContainer.call(this, ct);
    },

    renderItem : function(c, position, target){
        if (!this.itemTpl) {
            this.itemTpl = Ext.layout.MenuLayout.prototype.itemTpl = new Ext.XTemplate(
                '<li id="{itemId}" class="{itemCls}">',
                    '<tpl if="needsIcon">',
                        '<img src="{icon}" class="{iconCls}"/>',
                    '</tpl>',
                '</li>'
            );
        }

        if(c && !c.rendered){
            if(Ext.isNumber(position)){
                position = target.dom.childNodes[position];
            }
            var a = this.getItemArgs(c);


            c.render(c.positionEl = position ?
                this.itemTpl.insertBefore(position, a, true) :
                this.itemTpl.append(target, a, true));


            c.positionEl.menuItemId = c.getItemId();



            if (!a.isMenuItem && a.needsIcon) {
                c.positionEl.addClass('x-menu-list-item-indent');
            }
            this.configureItem(c, position);
        }else if(c && !this.isValidParent(c, target)){
            if(Ext.isNumber(position)){
                position = target.dom.childNodes[position];
            }
            target.dom.insertBefore(c.getActionEl().dom, position || null);
        }
    },

    getItemArgs : function(c) {
        var isMenuItem = c instanceof Ext.menu.Item;
        return {
            isMenuItem: isMenuItem,
            needsIcon: !isMenuItem && (c.icon || c.iconCls),
            icon: c.icon || Ext.BLANK_IMAGE_URL,
            iconCls: 'x-menu-item-icon ' + (c.iconCls || ''),
            itemId: 'x-menu-el-' + c.id,
            itemCls: 'x-menu-list-item '
        };
    },

    
    isValidParent : function(c, target) {
        return c.el.up('li.x-menu-list-item', 5).dom.parentNode === (target.dom || target);
    },

    onLayout : function(ct, target){
        Ext.layout.MenuLayout.superclass.onLayout.call(this, ct, target);
        this.doAutoSize();
    },

    doAutoSize : function(){
        var ct = this.container, w = ct.width;
        if(ct.floating){
            if(w){
                ct.setWidth(w);
            }else if(Ext.isIE){
                ct.setWidth(Ext.isStrict && (Ext.isIE7 || Ext.isIE8) ? 'auto' : ct.minWidth);
                var el = ct.getEl(), t = el.dom.offsetWidth; 
                ct.setWidth(ct.getLayoutTarget().getWidth() + el.getFrameWidth('lr'));
            }
        }
    }
});
Ext.Container.LAYOUTS['menu'] = Ext.layout.MenuLayout;

Ext.Viewport = Ext.extend(Ext.Container, {
    
    
    
    
    
    
    
    
    
    
    
    

    initComponent : function() {
        Ext.Viewport.superclass.initComponent.call(this);
        document.getElementsByTagName('html')[0].className += ' x-viewport';
        this.el = Ext.getBody();
        this.el.setHeight = Ext.emptyFn;
        this.el.setWidth = Ext.emptyFn;
        this.el.setSize = Ext.emptyFn;
        this.el.dom.scroll = 'no';
        this.allowDomMove = false;
        this.autoWidth = true;
        this.autoHeight = true;
        Ext.EventManager.onWindowResize(this.fireResize, this);
        this.renderTo = this.el;
    },

    fireResize : function(w, h){
        this.fireEvent('resize', this, w, h, w, h);
    }
});
Ext.reg('viewport', Ext.Viewport);

Ext.Panel = Ext.extend(Ext.Container, {
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    
    
    
    
    
    
    
    


    
    baseCls : 'x-panel',
    
    collapsedCls : 'x-panel-collapsed',
    
    maskDisabled : true,
    
    animCollapse : Ext.enableFx,
    
    headerAsText : true,
    
    buttonAlign : 'right',
    
    collapsed : false,
    
    collapseFirst : true,
    
    minButtonWidth : 75,
    
    
    elements : 'body',
    
    preventBodyReset : false,

    
    padding: undefined,

    
    resizeEvent: 'bodyresize',

    
    
    
    toolTarget : 'header',
    collapseEl : 'bwrap',
    slideAnchor : 't',
    disabledClass : '',

    
    deferHeight : true,
    
    expandDefaults: {
        duration : 0.25
    },
    
    collapseDefaults : {
        duration : 0.25
    },

    
    initComponent : function(){
        Ext.Panel.superclass.initComponent.call(this);

        this.addEvents(
            
            'bodyresize',
            
            'titlechange',
            
            'iconchange',
            
            'collapse',
            
            'expand',
            
            'beforecollapse',
            
            'beforeexpand',
            
            'beforeclose',
            
            'close',
            
            'activate',
            
            'deactivate'
        );

        if(this.unstyled){
            this.baseCls = 'x-plain';
        }


        this.toolbars = [];
        
        if(this.tbar){
            this.elements += ',tbar';
            this.topToolbar = this.createToolbar(this.tbar);
            this.tbar = null;

        }
        if(this.bbar){
            this.elements += ',bbar';
            this.bottomToolbar = this.createToolbar(this.bbar);
            this.bbar = null;
        }

        if(this.header === true){
            this.elements += ',header';
            this.header = null;
        }else if(this.headerCfg || (this.title && this.header !== false)){
            this.elements += ',header';
        }

        if(this.footerCfg || this.footer === true){
            this.elements += ',footer';
            this.footer = null;
        }

        if(this.buttons){
            this.fbar = this.buttons;
            this.buttons = null;
        }
        if(this.fbar){
            this.createFbar(this.fbar);
        }
        if(this.autoLoad){
            this.on('render', this.doAutoLoad, this, {delay:10});
        }
    },

    
    createFbar : function(fbar){
        var min = this.minButtonWidth;
        this.elements += ',footer';
        this.fbar = this.createToolbar(fbar, {
            buttonAlign: this.buttonAlign,
            toolbarCls: 'x-panel-fbar',
            enableOverflow: false,
            defaults: function(c){
                return {
                    minWidth: c.minWidth || min
                };
            }
        });
        
        
        
        this.fbar.items.each(function(c){
            c.minWidth = c.minWidth || this.minButtonWidth;
        }, this);
        this.buttons = this.fbar.items.items;
    },

    
    createToolbar: function(tb, options){
        var result;
        
        if(Ext.isArray(tb)){
            tb = {
                items: tb
            };
        }
        result = tb.events ? Ext.apply(tb, options) : this.createComponent(Ext.apply({}, tb, options), 'toolbar');
        this.toolbars.push(result);
        return result;
    },

    
    createElement : function(name, pnode){
        if(this[name]){
            pnode.appendChild(this[name].dom);
            return;
        }

        if(name === 'bwrap' || this.elements.indexOf(name) != -1){
            if(this[name+'Cfg']){
                this[name] = Ext.fly(pnode).createChild(this[name+'Cfg']);
            }else{
                var el = document.createElement('div');
                el.className = this[name+'Cls'];
                this[name] = Ext.get(pnode.appendChild(el));
            }
            if(this[name+'CssClass']){
                this[name].addClass(this[name+'CssClass']);
            }
            if(this[name+'Style']){
                this[name].applyStyles(this[name+'Style']);
            }
        }
    },

    
    onRender : function(ct, position){
        Ext.Panel.superclass.onRender.call(this, ct, position);
        this.createClasses();

        var el = this.el,
            d = el.dom,
            bw,
            ts;


        if(this.collapsible && !this.hideCollapseTool){
            this.tools = this.tools ? this.tools.slice(0) : [];
            this.tools[this.collapseFirst?'unshift':'push']({
                id: 'toggle',
                handler : this.toggleCollapse,
                scope: this
            });
        }

        if(this.tools){
            ts = this.tools;
            this.elements += (this.header !== false) ? ',header' : '';
        }
        this.tools = {};

        el.addClass(this.baseCls);
        if(d.firstChild){ 
            this.header = el.down('.'+this.headerCls);
            this.bwrap = el.down('.'+this.bwrapCls);
            var cp = this.bwrap ? this.bwrap : el;
            this.tbar = cp.down('.'+this.tbarCls);
            this.body = cp.down('.'+this.bodyCls);
            this.bbar = cp.down('.'+this.bbarCls);
            this.footer = cp.down('.'+this.footerCls);
            this.fromMarkup = true;
        }
        if (this.preventBodyReset === true) {
            el.addClass('x-panel-reset');
        }
        if(this.cls){
            el.addClass(this.cls);
        }

        if(this.buttons){
            this.elements += ',footer';
        }

        

        
        if(this.frame){
            el.insertHtml('afterBegin', String.format(Ext.Element.boxMarkup, this.baseCls));

            this.createElement('header', d.firstChild.firstChild.firstChild);
            this.createElement('bwrap', d);

            
            bw = this.bwrap.dom;
            var ml = d.childNodes[1], bl = d.childNodes[2];
            bw.appendChild(ml);
            bw.appendChild(bl);

            var mc = bw.firstChild.firstChild.firstChild;
            this.createElement('tbar', mc);
            this.createElement('body', mc);
            this.createElement('bbar', mc);
            this.createElement('footer', bw.lastChild.firstChild.firstChild);

            if(!this.footer){
                this.bwrap.dom.lastChild.className += ' x-panel-nofooter';
            }
            
            this.ft = Ext.get(this.bwrap.dom.lastChild);
            this.mc = Ext.get(mc);
        }else{
            this.createElement('header', d);
            this.createElement('bwrap', d);

            
            bw = this.bwrap.dom;
            this.createElement('tbar', bw);
            this.createElement('body', bw);
            this.createElement('bbar', bw);
            this.createElement('footer', bw);

            if(!this.header){
                this.body.addClass(this.bodyCls + '-noheader');
                if(this.tbar){
                    this.tbar.addClass(this.tbarCls + '-noheader');
                }
            }
        }

        if(Ext.isDefined(this.padding)){
            this.body.setStyle('padding', this.body.addUnits(this.padding));
        }

        if(this.border === false){
            this.el.addClass(this.baseCls + '-noborder');
            this.body.addClass(this.bodyCls + '-noborder');
            if(this.header){
                this.header.addClass(this.headerCls + '-noborder');
            }
            if(this.footer){
                this.footer.addClass(this.footerCls + '-noborder');
            }
            if(this.tbar){
                this.tbar.addClass(this.tbarCls + '-noborder');
            }
            if(this.bbar){
                this.bbar.addClass(this.bbarCls + '-noborder');
            }
        }

        if(this.bodyBorder === false){
           this.body.addClass(this.bodyCls + '-noborder');
        }

        this.bwrap.enableDisplayMode('block');

        if(this.header){
            this.header.unselectable();

            
            if(this.headerAsText){
                this.header.dom.innerHTML =
                    '<span class="' + this.headerTextCls + '">'+this.header.dom.innerHTML+'</span>';

                if(this.iconCls){
                    this.setIconClass(this.iconCls);
                }
            }
        }

        if(this.floating){
            this.makeFloating(this.floating);
        }

        if(this.collapsible && this.titleCollapse && this.header){
            this.mon(this.header, 'click', this.toggleCollapse, this);
            this.header.setStyle('cursor', 'pointer');
        }
        if(ts){
            this.addTool.apply(this, ts);
        }

        
        if(this.fbar){
            this.footer.addClass('x-panel-btns');
            this.fbar.ownerCt = this;
            this.fbar.render(this.footer);
            this.footer.createChild({cls:'x-clear'});
        }
        if(this.tbar && this.topToolbar){
            this.topToolbar.ownerCt = this;
            this.topToolbar.render(this.tbar);
        }
        if(this.bbar && this.bottomToolbar){
            this.bottomToolbar.ownerCt = this;
            this.bottomToolbar.render(this.bbar);
        }
    },

    
    setIconClass : function(cls){
        var old = this.iconCls;
        this.iconCls = cls;
        if(this.rendered && this.header){
            if(this.frame){
                this.header.addClass('x-panel-icon');
                this.header.replaceClass(old, this.iconCls);
            }else{
                var hd = this.header,
                    img = hd.child('img.x-panel-inline-icon');
                if(img){
                    Ext.fly(img).replaceClass(old, this.iconCls);
                }else{
                    var hdspan = hd.child('span.' + this.headerTextCls);
                    if (hdspan) {
                        Ext.DomHelper.insertBefore(hdspan.dom, {
                            tag:'img', src: Ext.BLANK_IMAGE_URL, cls:'x-panel-inline-icon '+this.iconCls
                        });
                    }
                 }
            }
        }
        this.fireEvent('iconchange', this, cls, old);
    },

    
    makeFloating : function(cfg){
        this.floating = true;
        this.el = new Ext.Layer(Ext.apply({}, cfg, {
            shadow: Ext.isDefined(this.shadow) ? this.shadow : 'sides',
            shadowOffset: this.shadowOffset,
            constrain:false,
            shim: this.shim === false ? false : undefined
        }), this.el);
    },

    
    getTopToolbar : function(){
        return this.topToolbar;
    },

    
    getBottomToolbar : function(){
        return this.bottomToolbar;
    },

    
    getFooterToolbar : function() {
        return this.fbar;
    },

    
    addButton : function(config, handler, scope){
        if(!this.fbar){
            this.createFbar([]);
        }
        if(handler){
            if(Ext.isString(config)){
                config = {text: config};
            }
            config = Ext.apply({
                handler: handler,
                scope: scope
            }, config);
        }
        return this.fbar.add(config);
    },

    
    addTool : function(){
        if(!this.rendered){
            if(!this.tools){
                this.tools = [];
            }
            Ext.each(arguments, function(arg){
                this.tools.push(arg);
            }, this);
            return;
        }
         
        if(!this[this.toolTarget]){
            return;
        }
        if(!this.toolTemplate){
            
            var tt = new Ext.Template(
                 '<div class="x-tool x-tool-{id}">&#160;</div>'
            );
            tt.disableFormats = true;
            tt.compile();
            Ext.Panel.prototype.toolTemplate = tt;
        }
        for(var i = 0, a = arguments, len = a.length; i < len; i++) {
            var tc = a[i];
            if(!this.tools[tc.id]){
                var overCls = 'x-tool-'+tc.id+'-over';
                var t = this.toolTemplate.insertFirst(this[this.toolTarget], tc, true);
                this.tools[tc.id] = t;
                t.enableDisplayMode('block');
                this.mon(t, 'click',  this.createToolHandler(t, tc, overCls, this));
                if(tc.on){
                    this.mon(t, tc.on);
                }
                if(tc.hidden){
                    t.hide();
                }
                if(tc.qtip){
                    if(Ext.isObject(tc.qtip)){
                        Ext.QuickTips.register(Ext.apply({
                              target: t.id
                        }, tc.qtip));
                    } else {
                        t.dom.qtip = tc.qtip;
                    }
                }
                t.addClassOnOver(overCls);
            }
        }
    },

    onLayout : function(shallow, force){
        Ext.Panel.superclass.onLayout.apply(this, arguments);
        if(this.hasLayout && this.toolbars.length > 0){
            Ext.each(this.toolbars, function(tb){
                tb.doLayout(undefined, force);
            });
            this.syncHeight();
        }
    },

    syncHeight : function(){
        var h = this.toolbarHeight,
                bd = this.body,
                lsh = this.lastSize.height,
                sz;

        if(this.autoHeight || !Ext.isDefined(lsh) || lsh == 'auto'){
            return;
        }


        if(h != this.getToolbarHeight()){
            h = Math.max(0, lsh - this.getFrameHeight());
            bd.setHeight(h);
            sz = bd.getSize();
            this.toolbarHeight = this.getToolbarHeight();
            this.onBodyResize(sz.width, sz.height);
        }
    },

    
    onShow : function(){
        if(this.floating){
            return this.el.show();
        }
        Ext.Panel.superclass.onShow.call(this);
    },

    
    onHide : function(){
        if(this.floating){
            return this.el.hide();
        }
        Ext.Panel.superclass.onHide.call(this);
    },

    
    createToolHandler : function(t, tc, overCls, panel){
        return function(e){
            t.removeClass(overCls);
            if(tc.stopEvent !== false){
                e.stopEvent();
            }
            if(tc.handler){
                tc.handler.call(tc.scope || t, e, t, panel, tc);
            }
        };
    },

    
    afterRender : function(){
        if(this.floating && !this.hidden){
            this.el.show();
        }
        if(this.title){
            this.setTitle(this.title);
        }
        Ext.Panel.superclass.afterRender.call(this); 
        if (this.collapsed) {
            this.collapsed = false;
            this.collapse(false);
        }
        this.initEvents();
    },

    
    getKeyMap : function(){
        if(!this.keyMap){
            this.keyMap = new Ext.KeyMap(this.el, this.keys);
        }
        return this.keyMap;
    },

    
    initEvents : function(){
        if(this.keys){
            this.getKeyMap();
        }
        if(this.draggable){
            this.initDraggable();
        }
        if(this.toolbars.length > 0){
            Ext.each(this.toolbars, function(tb){
                tb.doLayout();
                tb.on({
                    scope: this,
                    afterlayout: this.syncHeight,
                    remove: this.syncHeight
                });
            }, this);
            this.syncHeight();
        }

    },

    
    initDraggable : function(){
        
        this.dd = new Ext.Panel.DD(this, Ext.isBoolean(this.draggable) ? null : this.draggable);
    },

    
    beforeEffect : function(anim){
        if(this.floating){
            this.el.beforeAction();
        }
        if(anim !== false){
            this.el.addClass('x-panel-animated');
        }
    },

    
    afterEffect : function(anim){
        this.syncShadow();
        this.el.removeClass('x-panel-animated');
    },

    
    createEffect : function(a, cb, scope){
        var o = {
            scope:scope,
            block:true
        };
        if(a === true){
            o.callback = cb;
            return o;
        }else if(!a.callback){
            o.callback = cb;
        }else { 
            o.callback = function(){
                cb.call(scope);
                Ext.callback(a.callback, a.scope);
            };
        }
        return Ext.applyIf(o, a);
    },

    
    collapse : function(animate){
        if(this.collapsed || this.el.hasFxBlock() || this.fireEvent('beforecollapse', this, animate) === false){
            return;
        }
        var doAnim = animate === true || (animate !== false && this.animCollapse);
        this.beforeEffect(doAnim);
        this.onCollapse(doAnim, animate);
        return this;
    },

    
    onCollapse : function(doAnim, animArg){
        if(doAnim){
            this[this.collapseEl].slideOut(this.slideAnchor,
                    Ext.apply(this.createEffect(animArg||true, this.afterCollapse, this),
                        this.collapseDefaults));
        }else{
            this[this.collapseEl].hide(this.hideMode);
            this.afterCollapse(false);
        }
    },

    
    afterCollapse : function(anim){
        this.collapsed = true;
        this.el.addClass(this.collapsedCls);
        if(anim !== false){
            this[this.collapseEl].hide(this.hideMode);
        }
        this.afterEffect(anim);

        
        this.cascade(function(c) {
            if (c.lastSize) {
                c.lastSize = { width: undefined, height: undefined };
            }
        });
        this.fireEvent('collapse', this);
    },

    
    expand : function(animate){
        if(!this.collapsed || this.el.hasFxBlock() || this.fireEvent('beforeexpand', this, animate) === false){
            return;
        }
        var doAnim = animate === true || (animate !== false && this.animCollapse);
        this.el.removeClass(this.collapsedCls);
        this.beforeEffect(doAnim);
        this.onExpand(doAnim, animate);
        return this;
    },

    
    onExpand : function(doAnim, animArg){
        if(doAnim){
            this[this.collapseEl].slideIn(this.slideAnchor,
                    Ext.apply(this.createEffect(animArg||true, this.afterExpand, this),
                        this.expandDefaults));
        }else{
            this[this.collapseEl].show(this.hideMode);
            this.afterExpand(false);
        }
    },

    
    afterExpand : function(anim){
        this.collapsed = false;
        if(anim !== false){
            this[this.collapseEl].show(this.hideMode);
        }
        this.afterEffect(anim);
        if (this.deferLayout) {
            delete this.deferLayout;
            this.doLayout(true);
        }
        this.fireEvent('expand', this);
    },

    
    toggleCollapse : function(animate){
        this[this.collapsed ? 'expand' : 'collapse'](animate);
        return this;
    },

    
    onDisable : function(){
        if(this.rendered && this.maskDisabled){
            this.el.mask();
        }
        Ext.Panel.superclass.onDisable.call(this);
    },

    
    onEnable : function(){
        if(this.rendered && this.maskDisabled){
            this.el.unmask();
        }
        Ext.Panel.superclass.onEnable.call(this);
    },

    
    onResize : function(adjWidth, adjHeight, rawWidth, rawHeight){
        var w = adjWidth,
            h = adjHeight;

        if(Ext.isDefined(w) || Ext.isDefined(h)){
            if(!this.collapsed){
                
                
                

                if(Ext.isNumber(w)){
                    this.body.setWidth(w = this.adjustBodyWidth(w - this.getFrameWidth()));
                } else if (w == 'auto') {
                    w = this.body.setWidth('auto').dom.offsetWidth;
                } else {
                    w = this.body.dom.offsetWidth;
                }

                if(this.tbar){
                    this.tbar.setWidth(w);
                    if(this.topToolbar){
                        this.topToolbar.setSize(w);
                    }
                }
                if(this.bbar){
                    this.bbar.setWidth(w);
                    if(this.bottomToolbar){
                        this.bottomToolbar.setSize(w);
                        
                        if (Ext.isIE) {
                            this.bbar.setStyle('position', 'static');
                            this.bbar.setStyle('position', '');
                        }
                    }
                }
                if(this.footer){
                    this.footer.setWidth(w);
                    if(this.fbar){
                        this.fbar.setSize(Ext.isIE ? (w - this.footer.getFrameWidth('lr')) : 'auto');
                    }
                }

                
                if(Ext.isNumber(h)){
                    h = Math.max(0, h - this.getFrameHeight());
                    
                    this.body.setHeight(h);
                }else if(h == 'auto'){
                    this.body.setHeight(h);
                }

                if(this.disabled && this.el._mask){
                    this.el._mask.setSize(this.el.dom.clientWidth, this.el.getHeight());
                }
            }else{
                
                this.queuedBodySize = {width: w, height: h};
                if(!this.queuedExpand && this.allowQueuedExpand !== false){
                    this.queuedExpand = true;
                    this.on('expand', function(){
                        delete this.queuedExpand;
                        this.onResize(this.queuedBodySize.width, this.queuedBodySize.height);
                    }, this, {single:true});
                }
            }
            this.onBodyResize(w, h);
        }
        this.syncShadow();
        Ext.Panel.superclass.onResize.call(this, adjWidth, adjHeight, rawWidth, rawHeight);

    },

    
    onBodyResize: function(w, h){
        this.fireEvent('bodyresize', this, w, h);
    },

    
    getToolbarHeight: function(){
        var h = 0;
        if(this.rendered){
            Ext.each(this.toolbars, function(tb){
                h += tb.getHeight();
            }, this);
        }
        return h;
    },

    
    adjustBodyHeight : function(h){
        return h;
    },

    
    adjustBodyWidth : function(w){
        return w;
    },

    
    onPosition : function(){
        this.syncShadow();
    },

    
    getFrameWidth : function(){
        var w = this.el.getFrameWidth('lr') + this.bwrap.getFrameWidth('lr');

        if(this.frame){
            var l = this.bwrap.dom.firstChild;
            w += (Ext.fly(l).getFrameWidth('l') + Ext.fly(l.firstChild).getFrameWidth('r'));
            w += this.mc.getFrameWidth('lr');
        }
        return w;
    },

    
    getFrameHeight : function() {
        var h = Math.max(0, this.getHeight() - this.body.getHeight());

        if (isNaN(h)) {
            h = 0;
        }
        return h;

        
    },

    
    getInnerWidth : function(){
        return this.getSize().width - this.getFrameWidth();
    },

    
    getInnerHeight : function(){
        return this.body.getHeight();
        
    },

    
    syncShadow : function(){
        if(this.floating){
            this.el.sync(true);
        }
    },

    
    getLayoutTarget : function(){
        return this.body;
    },

    
    getContentTarget : function(){
        return this.body;
    },

    
    setTitle : function(title, iconCls){
        this.title = title;
        if(this.header && this.headerAsText){
            this.header.child('span').update(title);
        }
        if(iconCls){
            this.setIconClass(iconCls);
        }
        this.fireEvent('titlechange', this, title);
        return this;
    },

    
    getUpdater : function(){
        return this.body.getUpdater();
    },

     
    load : function(){
        var um = this.body.getUpdater();
        um.update.apply(um, arguments);
        return this;
    },

    
    beforeDestroy : function(){
        Ext.Panel.superclass.beforeDestroy.call(this);
        if(this.header){
            this.header.removeAllListeners();
        }
        if(this.tools){
            for(var k in this.tools){
                Ext.destroy(this.tools[k]);
            }
        }
        if(this.toolbars.length > 0){
            Ext.each(this.toolbars, function(tb){
                tb.un('afterlayout', this.syncHeight, this);
                tb.un('remove', this.syncHeight, this);
            }, this);
        }
        if(Ext.isArray(this.buttons)){
            while(this.buttons.length) {
                Ext.destroy(this.buttons[0]);
            }
        }
        if(this.rendered){
            Ext.destroy(
                this.ft,
                this.header,
                this.footer,
                this.tbar,
                this.bbar,
                this.body,
                this.mc,
                this.bwrap,
                this.dd
            );
            if (this.fbar) {
                Ext.destroy(
                    this.fbar,
                    this.fbar.el
                );
            }
        }
        Ext.destroy(this.toolbars);
    },

    
    createClasses : function(){
        this.headerCls = this.baseCls + '-header';
        this.headerTextCls = this.baseCls + '-header-text';
        this.bwrapCls = this.baseCls + '-bwrap';
        this.tbarCls = this.baseCls + '-tbar';
        this.bodyCls = this.baseCls + '-body';
        this.bbarCls = this.baseCls + '-bbar';
        this.footerCls = this.baseCls + '-footer';
    },

    
    createGhost : function(cls, useShim, appendTo){
        var el = document.createElement('div');
        el.className = 'x-panel-ghost ' + (cls ? cls : '');
        if(this.header){
            el.appendChild(this.el.dom.firstChild.cloneNode(true));
        }
        Ext.fly(el.appendChild(document.createElement('ul'))).setHeight(this.bwrap.getHeight());
        el.style.width = this.el.dom.offsetWidth + 'px';;
        if(!appendTo){
            this.container.dom.appendChild(el);
        }else{
            Ext.getDom(appendTo).appendChild(el);
        }
        if(useShim !== false && this.el.useShim !== false){
            var layer = new Ext.Layer({shadow:false, useDisplay:true, constrain:false}, el);
            layer.show();
            return layer;
        }else{
            return new Ext.Element(el);
        }
    },

    
    doAutoLoad : function(){
        var u = this.body.getUpdater();
        if(this.renderer){
            u.setRenderer(this.renderer);
        }
        u.update(Ext.isObject(this.autoLoad) ? this.autoLoad : {url: this.autoLoad});
    },

    
    getTool : function(id) {
        return this.tools[id];
    }


});
Ext.reg('panel', Ext.Panel);

Ext.Editor = function(field, config){
    if(field.field){
        this.field = Ext.create(field.field, 'textfield');
        config = Ext.apply({}, field); 
        delete config.field;
    }else{
        this.field = field;
    }
    Ext.Editor.superclass.constructor.call(this, config);
};

Ext.extend(Ext.Editor, Ext.Component, {
    
    
    allowBlur: true,
    
    
    
    
    
    value : "",
    
    alignment: "c-c?",
    
    offsets: [0, 0],
    
    shadow : "frame",
    
    constrain : false,
    
    swallowKeys : true,
    
    completeOnEnter : true,
    
    cancelOnEsc : true,
    
    updateEl : false,

    initComponent : function(){
        Ext.Editor.superclass.initComponent.call(this);
        this.addEvents(
            
            "beforestartedit",
            
            "startedit",
            
            "beforecomplete",
            
            "complete",
            
            "canceledit",
            
            "specialkey"
        );
    },

    
    onRender : function(ct, position){
        this.el = new Ext.Layer({
            shadow: this.shadow,
            cls: "x-editor",
            parentEl : ct,
            shim : this.shim,
            shadowOffset: this.shadowOffset || 4,
            id: this.id,
            constrain: this.constrain
        });
        if(this.zIndex){
            this.el.setZIndex(this.zIndex);
        }
        this.el.setStyle("overflow", Ext.isGecko ? "auto" : "hidden");
        if(this.field.msgTarget != 'title'){
            this.field.msgTarget = 'qtip';
        }
        this.field.inEditor = true;
        this.mon(this.field, {
            scope: this,
            blur: this.onBlur,
            specialkey: this.onSpecialKey
        });
        if(this.field.grow){
            this.mon(this.field, "autosize", this.el.sync,  this.el, {delay:1});
        }
        this.field.render(this.el).show();
        this.field.getEl().dom.name = '';
        if(this.swallowKeys){
            this.field.el.swallowEvent([
                'keypress', 
                'keydown'   
            ]);
        }
    },

    
    onSpecialKey : function(field, e){
        var key = e.getKey(),
            complete = this.completeOnEnter && key == e.ENTER,
            cancel = this.cancelOnEsc && key == e.ESC;
        if(complete || cancel){
            e.stopEvent();
            if(complete){
                this.completeEdit();
            }else{
                this.cancelEdit();
            }
            if(field.triggerBlur){
                field.triggerBlur();
            }
        }
        this.fireEvent('specialkey', field, e);
    },

    
    startEdit : function(el, value){
        if(this.editing){
            this.completeEdit();
        }
        this.boundEl = Ext.get(el);
        var v = value !== undefined ? value : this.boundEl.dom.innerHTML;
        if(!this.rendered){
            this.render(this.parentEl || document.body);
        }
        if(this.fireEvent("beforestartedit", this, this.boundEl, v) !== false){
            this.startValue = v;
            this.field.reset();
            this.field.setValue(v);
            this.realign(true);
            this.editing = true;
            this.show();
        }
    },

    
    doAutoSize : function(){
        if(this.autoSize){
            var sz = this.boundEl.getSize(),
                fs = this.field.getSize();

            switch(this.autoSize){
                case "width":
                    this.setSize(sz.width, fs.height);
                    break;
                case "height":
                    this.setSize(fs.width, sz.height);
                    break;
                case "none":
                    this.setSize(fs.width, fs.height);
                    break;
                default:
                    this.setSize(sz.width, sz.height);
            }
        }
    },

    
    setSize : function(w, h){
        delete this.field.lastSize;
        this.field.setSize(w, h);
        if(this.el){
            if(Ext.isGecko2 || Ext.isOpera){
                
                this.el.setSize(w, h);
            }
            this.el.sync();
        }
    },

    
    realign : function(autoSize){
        if(autoSize === true){
            this.doAutoSize();
        }
        this.el.alignTo(this.boundEl, this.alignment, this.offsets);
    },

    
    completeEdit : function(remainVisible){
        if(!this.editing){
            return;
        }
        
        if (this.field.assertValue) {
            this.field.assertValue();
        }
        var v = this.getValue();
        if(!this.field.isValid()){
            if(this.revertInvalid !== false){
                this.cancelEdit(remainVisible);
            }
            return;
        }
        if(String(v) === String(this.startValue) && this.ignoreNoChange){
            this.hideEdit(remainVisible);
            return;
        }
        if(this.fireEvent("beforecomplete", this, v, this.startValue) !== false){
            v = this.getValue();
            if(this.updateEl && this.boundEl){
                this.boundEl.update(v);
            }
            this.hideEdit(remainVisible);
            this.fireEvent("complete", this, v, this.startValue);
        }
    },

    
    onShow : function(){
        this.el.show();
        if(this.hideEl !== false){
            this.boundEl.hide();
        }
        this.field.show().focus(false, true);
        this.fireEvent("startedit", this.boundEl, this.startValue);
    },

    
    cancelEdit : function(remainVisible){
        if(this.editing){
            var v = this.getValue();
            this.setValue(this.startValue);
            this.hideEdit(remainVisible);
            this.fireEvent("canceledit", this, v, this.startValue);
        }
    },

    
    hideEdit: function(remainVisible){
        if(remainVisible !== true){
            this.editing = false;
            this.hide();
        }
    },

    
    onBlur : function(){
        
        if(this.allowBlur === true && this.editing && this.selectSameEditor !== true){
            this.completeEdit();
        }
    },

    
    onHide : function(){
        if(this.editing){
            this.completeEdit();
            return;
        }
        this.field.blur();
        if(this.field.collapse){
            this.field.collapse();
        }
        this.el.hide();
        if(this.hideEl !== false){
            this.boundEl.show();
        }
    },

    
    setValue : function(v){
        this.field.setValue(v);
    },

    
    getValue : function(){
        return this.field.getValue();
    },

    beforeDestroy : function(){
        Ext.destroyMembers(this, 'field');

        delete this.parentEl;
        delete this.boundEl;
    }
});
Ext.reg('editor', Ext.Editor);

Ext.ColorPalette = Ext.extend(Ext.Component, {
	
    
    itemCls : 'x-color-palette',
    
    value : null,
    
    clickEvent :'click',
    
    ctype : 'Ext.ColorPalette',

    
    allowReselect : false,

    
    colors : [
        '000000', '993300', '333300', '003300', '003366', '000080', '333399', '333333',
        '800000', 'FF6600', '808000', '008000', '008080', '0000FF', '666699', '808080',
        'FF0000', 'FF9900', '99CC00', '339966', '33CCCC', '3366FF', '800080', '969696',
        'FF00FF', 'FFCC00', 'FFFF00', '00FF00', '00FFFF', '00CCFF', '993366', 'C0C0C0',
        'FF99CC', 'FFCC99', 'FFFF99', 'CCFFCC', 'CCFFFF', '99CCFF', 'CC99FF', 'FFFFFF'
    ],

    
    
    
    
    initComponent : function(){
        Ext.ColorPalette.superclass.initComponent.call(this);
        this.addEvents(
            
            'select'
        );

        if(this.handler){
            this.on('select', this.handler, this.scope, true);
        }    
    },

    
    onRender : function(container, position){
        this.autoEl = {
            tag: 'div',
            cls: this.itemCls
        };
        Ext.ColorPalette.superclass.onRender.call(this, container, position);
        var t = this.tpl || new Ext.XTemplate(
            '<tpl for="."><a href="#" class="color-{.}" hidefocus="on"><em><span style="background:#{.}" unselectable="on">&#160;</span></em></a></tpl>'
        );
        t.overwrite(this.el, this.colors);
        this.mon(this.el, this.clickEvent, this.handleClick, this, {delegate: 'a'});
        if(this.clickEvent != 'click'){
        	this.mon(this.el, 'click', Ext.emptyFn, this, {delegate: 'a', preventDefault: true});
        }
    },

    
    afterRender : function(){
        Ext.ColorPalette.superclass.afterRender.call(this);
        if(this.value){
            var s = this.value;
            this.value = null;
            this.select(s, true);
        }
    },

    
    handleClick : function(e, t){
        e.preventDefault();
        if(!this.disabled){
            var c = t.className.match(/(?:^|\s)color-(.{6})(?:\s|$)/)[1];
            this.select(c.toUpperCase());
        }
    },

    
    select : function(color, suppressEvent){
        color = color.replace('#', '');
        if(color != this.value || this.allowReselect){
            var el = this.el;
            if(this.value){
                el.child('a.color-'+this.value).removeClass('x-color-palette-sel');
            }
            el.child('a.color-'+color).addClass('x-color-palette-sel');
            this.value = color;
            if(suppressEvent !== true){
                this.fireEvent('select', this, color);
            }
        }
    }

    
});
Ext.reg('colorpalette', Ext.ColorPalette);
Ext.DatePicker = Ext.extend(Ext.BoxComponent, {
    
    todayText : 'Today',
    
    okText : '&#160;OK&#160;',
    
    cancelText : 'Cancel',
    
    
    
    todayTip : '{0} (Spacebar)',
    
    minText : 'This date is before the minimum date',
    
    maxText : 'This date is after the maximum date',
    
    format : 'm/d/y',
    
    disabledDaysText : 'Disabled',
    
    disabledDatesText : 'Disabled',
    
    monthNames : Date.monthNames,
    
    dayNames : Date.dayNames,
    
    nextText : 'Next Month (Control+Right)',
    
    prevText : 'Previous Month (Control+Left)',
    
    monthYearText : 'Choose a month (Control+Up/Down to move years)',
    
    startDay : 0,
    
    showToday : true,
    
    
    
    
    

    
    
    focusOnSelect: true,

    
    
    initHour: 12, 

    
    initComponent : function(){
        Ext.DatePicker.superclass.initComponent.call(this);

        this.value = this.value ?
                 this.value.clearTime(true) : new Date().clearTime();

        this.addEvents(
            
            'select'
        );

        if(this.handler){
            this.on('select', this.handler,  this.scope || this);
        }

        this.initDisabledDays();
    },

    
    initDisabledDays : function(){
        if(!this.disabledDatesRE && this.disabledDates){
            var dd = this.disabledDates,
                len = dd.length - 1,
                re = '(?:';

            Ext.each(dd, function(d, i){
                re += Ext.isDate(d) ? '^' + Ext.escapeRe(d.dateFormat(this.format)) + '$' : dd[i];
                if(i != len){
                    re += '|';
                }
            }, this);
            this.disabledDatesRE = new RegExp(re + ')');
        }
    },

    
    setDisabledDates : function(dd){
        if(Ext.isArray(dd)){
            this.disabledDates = dd;
            this.disabledDatesRE = null;
        }else{
            this.disabledDatesRE = dd;
        }
        this.initDisabledDays();
        this.update(this.value, true);
    },

    
    setDisabledDays : function(dd){
        this.disabledDays = dd;
        this.update(this.value, true);
    },

    
    setMinDate : function(dt){
        this.minDate = dt;
        this.update(this.value, true);
    },

    
    setMaxDate : function(dt){
        this.maxDate = dt;
        this.update(this.value, true);
    },

    
    setValue : function(value){
        this.value = value.clearTime(true);
        this.update(this.value);
    },

    
    getValue : function(){
        return this.value;
    },

    
    focus : function(){
        this.update(this.activeDate);
    },

    
    onEnable: function(initial){
        Ext.DatePicker.superclass.onEnable.call(this);
        this.doDisabled(false);
        this.update(initial ? this.value : this.activeDate);
        if(Ext.isIE){
            this.el.repaint();
        }

    },

    
    onDisable : function(){
        Ext.DatePicker.superclass.onDisable.call(this);
        this.doDisabled(true);
        if(Ext.isIE && !Ext.isIE8){
            
             Ext.each([].concat(this.textNodes, this.el.query('th span')), function(el){
                 Ext.fly(el).repaint();
             });
        }
    },

    
    doDisabled : function(disabled){
        this.keyNav.setDisabled(disabled);
        this.prevRepeater.setDisabled(disabled);
        this.nextRepeater.setDisabled(disabled);
        if(this.showToday){
            this.todayKeyListener.setDisabled(disabled);
            this.todayBtn.setDisabled(disabled);
        }
    },

    
    onRender : function(container, position){
        var m = [
             '<table cellspacing="0">',
                '<tr><td class="x-date-left"><a href="#" title="', this.prevText ,'">&#160;</a></td><td class="x-date-middle" align="center"></td><td class="x-date-right"><a href="#" title="', this.nextText ,'">&#160;</a></td></tr>',
                '<tr><td colspan="3"><table class="x-date-inner" cellspacing="0"><thead><tr>'],
                dn = this.dayNames,
                i;
        for(i = 0; i < 7; i++){
            var d = this.startDay+i;
            if(d > 6){
                d = d-7;
            }
            m.push('<th><span>', dn[d].substr(0,1), '</span></th>');
        }
        m[m.length] = '</tr></thead><tbody><tr>';
        for(i = 0; i < 42; i++) {
            if(i % 7 === 0 && i !== 0){
                m[m.length] = '</tr><tr>';
            }
            m[m.length] = '<td><a href="#" hidefocus="on" class="x-date-date" tabIndex="1"><em><span></span></em></a></td>';
        }
        m.push('</tr></tbody></table></td></tr>',
                this.showToday ? '<tr><td colspan="3" class="x-date-bottom" align="center"></td></tr>' : '',
                '</table><div class="x-date-mp"></div>');

        var el = document.createElement('div');
        el.className = 'x-date-picker';
        el.innerHTML = m.join('');

        container.dom.insertBefore(el, position);

        this.el = Ext.get(el);
        this.eventEl = Ext.get(el.firstChild);

        this.prevRepeater = new Ext.util.ClickRepeater(this.el.child('td.x-date-left a'), {
            handler: this.showPrevMonth,
            scope: this,
            preventDefault:true,
            stopDefault:true
        });

        this.nextRepeater = new Ext.util.ClickRepeater(this.el.child('td.x-date-right a'), {
            handler: this.showNextMonth,
            scope: this,
            preventDefault:true,
            stopDefault:true
        });

        this.monthPicker = this.el.down('div.x-date-mp');
        this.monthPicker.enableDisplayMode('block');

        this.keyNav = new Ext.KeyNav(this.eventEl, {
            'left' : function(e){
                if(e.ctrlKey){
                    this.showPrevMonth();
                }else{
                    this.update(this.activeDate.add('d', -1));
                }
            },

            'right' : function(e){
                if(e.ctrlKey){
                    this.showNextMonth();
                }else{
                    this.update(this.activeDate.add('d', 1));
                }
            },

            'up' : function(e){
                if(e.ctrlKey){
                    this.showNextYear();
                }else{
                    this.update(this.activeDate.add('d', -7));
                }
            },

            'down' : function(e){
                if(e.ctrlKey){
                    this.showPrevYear();
                }else{
                    this.update(this.activeDate.add('d', 7));
                }
            },

            'pageUp' : function(e){
                this.showNextMonth();
            },

            'pageDown' : function(e){
                this.showPrevMonth();
            },

            'enter' : function(e){
                e.stopPropagation();
                return true;
            },

            scope : this
        });

        this.el.unselectable();

        this.cells = this.el.select('table.x-date-inner tbody td');
        this.textNodes = this.el.query('table.x-date-inner tbody span');

        this.mbtn = new Ext.Button({
            text: '&#160;',
            tooltip: this.monthYearText,
            renderTo: this.el.child('td.x-date-middle', true)
        });
        this.mbtn.el.child('em').addClass('x-btn-arrow');

        if(this.showToday){
            this.todayKeyListener = this.eventEl.addKeyListener(Ext.EventObject.SPACE, this.selectToday,  this);
            var today = (new Date()).dateFormat(this.format);
            this.todayBtn = new Ext.Button({
                renderTo: this.el.child('td.x-date-bottom', true),
                text: String.format(this.todayText, today),
                tooltip: String.format(this.todayTip, today),
                handler: this.selectToday,
                scope: this
            });
        }
        this.mon(this.eventEl, 'mousewheel', this.handleMouseWheel, this);
        this.mon(this.eventEl, 'click', this.handleDateClick,  this, {delegate: 'a.x-date-date'});
        this.mon(this.mbtn, 'click', this.showMonthPicker, this);
        this.onEnable(true);
    },

    
    createMonthPicker : function(){
        if(!this.monthPicker.dom.firstChild){
            var buf = ['<table border="0" cellspacing="0">'];
            for(var i = 0; i < 6; i++){
                buf.push(
                    '<tr><td class="x-date-mp-month"><a href="#">', Date.getShortMonthName(i), '</a></td>',
                    '<td class="x-date-mp-month x-date-mp-sep"><a href="#">', Date.getShortMonthName(i + 6), '</a></td>',
                    i === 0 ?
                    '<td class="x-date-mp-ybtn" align="center"><a class="x-date-mp-prev"></a></td><td class="x-date-mp-ybtn" align="center"><a class="x-date-mp-next"></a></td></tr>' :
                    '<td class="x-date-mp-year"><a href="#"></a></td><td class="x-date-mp-year"><a href="#"></a></td></tr>'
                );
            }
            buf.push(
                '<tr class="x-date-mp-btns"><td colspan="4"><button type="button" class="x-date-mp-ok">',
                    this.okText,
                    '</button><button type="button" class="x-date-mp-cancel">',
                    this.cancelText,
                    '</button></td></tr>',
                '</table>'
            );
            this.monthPicker.update(buf.join(''));

            this.mon(this.monthPicker, 'click', this.onMonthClick, this);
            this.mon(this.monthPicker, 'dblclick', this.onMonthDblClick, this);

            this.mpMonths = this.monthPicker.select('td.x-date-mp-month');
            this.mpYears = this.monthPicker.select('td.x-date-mp-year');

            this.mpMonths.each(function(m, a, i){
                i += 1;
                if((i%2) === 0){
                    m.dom.xmonth = 5 + Math.round(i * 0.5);
                }else{
                    m.dom.xmonth = Math.round((i-1) * 0.5);
                }
            });
        }
    },

    
    showMonthPicker : function(){
        if(!this.disabled){
            this.createMonthPicker();
            var size = this.el.getSize();
            this.monthPicker.setSize(size);
            this.monthPicker.child('table').setSize(size);

            this.mpSelMonth = (this.activeDate || this.value).getMonth();
            this.updateMPMonth(this.mpSelMonth);
            this.mpSelYear = (this.activeDate || this.value).getFullYear();
            this.updateMPYear(this.mpSelYear);

            this.monthPicker.slideIn('t', {duration:0.2});
        }
    },

    
    updateMPYear : function(y){
        this.mpyear = y;
        var ys = this.mpYears.elements;
        for(var i = 1; i <= 10; i++){
            var td = ys[i-1], y2;
            if((i%2) === 0){
                y2 = y + Math.round(i * 0.5);
                td.firstChild.innerHTML = y2;
                td.xyear = y2;
            }else{
                y2 = y - (5-Math.round(i * 0.5));
                td.firstChild.innerHTML = y2;
                td.xyear = y2;
            }
            this.mpYears.item(i-1)[y2 == this.mpSelYear ? 'addClass' : 'removeClass']('x-date-mp-sel');
        }
    },

    
    updateMPMonth : function(sm){
        this.mpMonths.each(function(m, a, i){
            m[m.dom.xmonth == sm ? 'addClass' : 'removeClass']('x-date-mp-sel');
        });
    },

    
    selectMPMonth : function(m){

    },

    
    onMonthClick : function(e, t){
        e.stopEvent();
        var el = new Ext.Element(t), pn;
        if(el.is('button.x-date-mp-cancel')){
            this.hideMonthPicker();
        }
        else if(el.is('button.x-date-mp-ok')){
            var d = new Date(this.mpSelYear, this.mpSelMonth, (this.activeDate || this.value).getDate());
            if(d.getMonth() != this.mpSelMonth){
                
                d = new Date(this.mpSelYear, this.mpSelMonth, 1).getLastDateOfMonth();
            }
            this.update(d);
            this.hideMonthPicker();
        }
        else if((pn = el.up('td.x-date-mp-month', 2))){
            this.mpMonths.removeClass('x-date-mp-sel');
            pn.addClass('x-date-mp-sel');
            this.mpSelMonth = pn.dom.xmonth;
        }
        else if((pn = el.up('td.x-date-mp-year', 2))){
            this.mpYears.removeClass('x-date-mp-sel');
            pn.addClass('x-date-mp-sel');
            this.mpSelYear = pn.dom.xyear;
        }
        else if(el.is('a.x-date-mp-prev')){
            this.updateMPYear(this.mpyear-10);
        }
        else if(el.is('a.x-date-mp-next')){
            this.updateMPYear(this.mpyear+10);
        }
    },

    
    onMonthDblClick : function(e, t){
        e.stopEvent();
        var el = new Ext.Element(t), pn;
        if((pn = el.up('td.x-date-mp-month', 2))){
            this.update(new Date(this.mpSelYear, pn.dom.xmonth, (this.activeDate || this.value).getDate()));
            this.hideMonthPicker();
        }
        else if((pn = el.up('td.x-date-mp-year', 2))){
            this.update(new Date(pn.dom.xyear, this.mpSelMonth, (this.activeDate || this.value).getDate()));
            this.hideMonthPicker();
        }
    },

    
    hideMonthPicker : function(disableAnim){
        if(this.monthPicker){
            if(disableAnim === true){
                this.monthPicker.hide();
            }else{
                this.monthPicker.slideOut('t', {duration:0.2});
            }
        }
    },

    
    showPrevMonth : function(e){
        this.update(this.activeDate.add('mo', -1));
    },

    
    showNextMonth : function(e){
        this.update(this.activeDate.add('mo', 1));
    },

    
    showPrevYear : function(){
        this.update(this.activeDate.add('y', -1));
    },

    
    showNextYear : function(){
        this.update(this.activeDate.add('y', 1));
    },

    
    handleMouseWheel : function(e){
        e.stopEvent();
        if(!this.disabled){
            var delta = e.getWheelDelta();
            if(delta > 0){
                this.showPrevMonth();
            } else if(delta < 0){
                this.showNextMonth();
            }
        }
    },

    
    handleDateClick : function(e, t){
        e.stopEvent();
        if(!this.disabled && t.dateValue && !Ext.fly(t.parentNode).hasClass('x-date-disabled')){
            this.cancelFocus = this.focusOnSelect === false;
            this.setValue(new Date(t.dateValue));
            delete this.cancelFocus;
            this.fireEvent('select', this, this.value);
        }
    },

    
    selectToday : function(){
        if(this.todayBtn && !this.todayBtn.disabled){
            this.setValue(new Date().clearTime());
            this.fireEvent('select', this, this.value);
        }
    },

    
    update : function(date, forceRefresh){
        if(this.rendered){
            var vd = this.activeDate, vis = this.isVisible();
            this.activeDate = date;
            if(!forceRefresh && vd && this.el){
                var t = date.getTime();
                if(vd.getMonth() == date.getMonth() && vd.getFullYear() == date.getFullYear()){
                    this.cells.removeClass('x-date-selected');
                    this.cells.each(function(c){
                       if(c.dom.firstChild.dateValue == t){
                           c.addClass('x-date-selected');
                           if(vis && !this.cancelFocus){
                               Ext.fly(c.dom.firstChild).focus(50);
                           }
                           return false;
                       }
                    }, this);
                    return;
                }
            }
            var days = date.getDaysInMonth(),
                firstOfMonth = date.getFirstDateOfMonth(),
                startingPos = firstOfMonth.getDay()-this.startDay;

            if(startingPos < 0){
                startingPos += 7;
            }
            days += startingPos;

            var pm = date.add('mo', -1),
                prevStart = pm.getDaysInMonth()-startingPos,
                cells = this.cells.elements,
                textEls = this.textNodes,
                
                d = (new Date(pm.getFullYear(), pm.getMonth(), prevStart, this.initHour)),
                today = new Date().clearTime().getTime(),
                sel = date.clearTime(true).getTime(),
                min = this.minDate ? this.minDate.clearTime(true) : Number.NEGATIVE_INFINITY,
                max = this.maxDate ? this.maxDate.clearTime(true) : Number.POSITIVE_INFINITY,
                ddMatch = this.disabledDatesRE,
                ddText = this.disabledDatesText,
                ddays = this.disabledDays ? this.disabledDays.join('') : false,
                ddaysText = this.disabledDaysText,
                format = this.format;

            if(this.showToday){
                var td = new Date().clearTime(),
                    disable = (td < min || td > max ||
                    (ddMatch && format && ddMatch.test(td.dateFormat(format))) ||
                    (ddays && ddays.indexOf(td.getDay()) != -1));

                if(!this.disabled){
                    this.todayBtn.setDisabled(disable);
                    this.todayKeyListener[disable ? 'disable' : 'enable']();
                }
            }

            var setCellClass = function(cal, cell){
                cell.title = '';
                var t = d.clearTime(true).getTime();
                cell.firstChild.dateValue = t;
                if(t == today){
                    cell.className += ' x-date-today';
                    cell.title = cal.todayText;
                }
                if(t == sel){
                    cell.className += ' x-date-selected';
                    if(vis){
                        Ext.fly(cell.firstChild).focus(50);
                    }
                }
                
                if(t < min) {
                    cell.className = ' x-date-disabled';
                    cell.title = cal.minText;
                    return;
                }
                if(t > max) {
                    cell.className = ' x-date-disabled';
                    cell.title = cal.maxText;
                    return;
                }
                if(ddays){
                    if(ddays.indexOf(d.getDay()) != -1){
                        cell.title = ddaysText;
                        cell.className = ' x-date-disabled';
                    }
                }
                if(ddMatch && format){
                    var fvalue = d.dateFormat(format);
                    if(ddMatch.test(fvalue)){
                        cell.title = ddText.replace('%0', fvalue);
                        cell.className = ' x-date-disabled';
                    }
                }
            };

            var i = 0;
            for(; i < startingPos; i++) {
                textEls[i].innerHTML = (++prevStart);
                d.setDate(d.getDate()+1);
                cells[i].className = 'x-date-prevday';
                setCellClass(this, cells[i]);
            }
            for(; i < days; i++){
                var intDay = i - startingPos + 1;
                textEls[i].innerHTML = (intDay);
                d.setDate(d.getDate()+1);
                cells[i].className = 'x-date-active';
                setCellClass(this, cells[i]);
            }
            var extraDays = 0;
            for(; i < 42; i++) {
                 textEls[i].innerHTML = (++extraDays);
                 d.setDate(d.getDate()+1);
                 cells[i].className = 'x-date-nextday';
                 setCellClass(this, cells[i]);
            }

            this.mbtn.setText(this.monthNames[date.getMonth()] + ' ' + date.getFullYear());

            if(!this.internalRender){
                var main = this.el.dom.firstChild,
                    w = main.offsetWidth;
                this.el.setWidth(w + this.el.getBorderWidth('lr'));
                Ext.fly(main).setWidth(w);
                this.internalRender = true;
                
                
                
                if(Ext.isOpera && !this.secondPass){
                    main.rows[0].cells[1].style.width = (w - (main.rows[0].cells[0].offsetWidth+main.rows[0].cells[2].offsetWidth)) + 'px';
                    this.secondPass = true;
                    this.update.defer(10, this, [date]);
                }
            }
        }
    },

    
    beforeDestroy : function() {
        if(this.rendered){
            Ext.destroy(
                this.keyNav,
                this.monthPicker,
                this.eventEl,
                this.mbtn,
                this.nextRepeater,
                this.prevRepeater,
                this.cells.el,
                this.todayBtn
            );
            delete this.textNodes;
            delete this.cells.elements;
        }
    }

    
});

Ext.reg('datepicker', Ext.DatePicker);

Ext.LoadMask = function(el, config){
    this.el = Ext.get(el);
    Ext.apply(this, config);
    if(this.store){
        this.store.on({
            scope: this,
            beforeload: this.onBeforeLoad,
            load: this.onLoad,
            exception: this.onLoad
        });
        this.removeMask = Ext.value(this.removeMask, false);
    }else{
        var um = this.el.getUpdater();
        um.showLoadIndicator = false; 
        um.on({
            scope: this,
            beforeupdate: this.onBeforeLoad,
            update: this.onLoad,
            failure: this.onLoad
        });
        this.removeMask = Ext.value(this.removeMask, true);
    }
};

Ext.LoadMask.prototype = {
    
    
    
    msg : 'Loading...',
    
    msgCls : 'x-mask-loading',

    
    disabled: false,

    
    disable : function(){
       this.disabled = true;
    },

    
    enable : function(){
        this.disabled = false;
    },

    
    onLoad : function(){
        this.el.unmask(this.removeMask);
    },

    
    onBeforeLoad : function(){
        if(!this.disabled){
            this.el.mask(this.msg, this.msgCls);
        }
    },

    
    show: function(){
        this.onBeforeLoad();
    },

    
    hide: function(){
        this.onLoad();
    },

    
    destroy : function(){
        if(this.store){
            this.store.un('beforeload', this.onBeforeLoad, this);
            this.store.un('load', this.onLoad, this);
            this.store.un('exception', this.onLoad, this);
        }else{
            var um = this.el.getUpdater();
            um.un('beforeupdate', this.onBeforeLoad, this);
            um.un('update', this.onLoad, this);
            um.un('failure', this.onLoad, this);
        }
    }
};Ext.ns('Ext.slider');


Ext.slider.Thumb = Ext.extend(Object, {

    
    constructor: function(config) {
        
        Ext.apply(this, config || {}, {
            cls: 'x-slider-thumb',

            
            constrain: false
        });

        Ext.slider.Thumb.superclass.constructor.call(this, config);

        if (this.slider.vertical) {
            Ext.apply(this, Ext.slider.Thumb.Vertical);
        }
    },

    
    render: function() {
        this.el = this.slider.innerEl.insertFirst({cls: this.cls});

        this.initEvents();
    },

    
    enable: function() {
        this.disabled = false;
        this.el.removeClass(this.slider.disabledClass);
    },

    
    disable: function() {
        this.disabled = true;
        this.el.addClass(this.slider.disabledClass);
    },

    
    initEvents: function() {
        var el = this.el;

        el.addClassOnOver('x-slider-thumb-over');

        this.tracker = new Ext.dd.DragTracker({
            onBeforeStart: this.onBeforeDragStart.createDelegate(this),
            onStart      : this.onDragStart.createDelegate(this),
            onDrag       : this.onDrag.createDelegate(this),
            onEnd        : this.onDragEnd.createDelegate(this),
            tolerance    : 3,
            autoStart    : 300
        });

        this.tracker.initEl(el);
    },

    
    onBeforeDragStart : function(e) {
        if (this.disabled) {
            return false;
        } else {
            this.slider.promoteThumb(this);
            return true;
        }
    },

    
    onDragStart: function(e){
        this.el.addClass('x-slider-thumb-drag');
        this.dragging = true;
        this.dragStartValue = this.value;

        this.slider.fireEvent('dragstart', this.slider, e, this);
    },

    
    onDrag: function(e) {
        var slider   = this.slider,
            index    = this.index,
            newValue = this.getNewValue();

        if (this.constrain) {
            var above = slider.thumbs[index + 1],
                below = slider.thumbs[index - 1];

            if (below != undefined && newValue <= below.value) newValue = below.value;
            if (above != undefined && newValue >= above.value) newValue = above.value;
        }

        slider.setValue(index, newValue, false);
        slider.fireEvent('drag', slider, e, this);
    },

    getNewValue: function() {
        var slider   = this.slider,
            pos      = slider.innerEl.translatePoints(this.tracker.getXY());

        return Ext.util.Format.round(slider.reverseValue(pos.left), slider.decimalPrecision);
    },

    
    onDragEnd: function(e) {
        var slider = this.slider,
            value  = this.value;

        this.el.removeClass('x-slider-thumb-drag');

        this.dragging = false;
        slider.fireEvent('dragend', slider, e);

        if (this.dragStartValue != value) {
            slider.fireEvent('changecomplete', slider, value, this);
        }
    }
});


Ext.slider.MultiSlider = Ext.extend(Ext.BoxComponent, {
    
    
    vertical: false,
    
    minValue: 0,
    
    maxValue: 100,
    
    decimalPrecision: 0,
    
    keyIncrement: 1,
    
    increment: 0,

    
    clickRange: [5,15],

    
    clickToChange : true,
    
    animate: true,

    
    dragging: false,

    
    constrainThumbs: true,

    
    topThumbZIndex: 10000,

    
    initComponent : function(){
        if(!Ext.isDefined(this.value)){
            this.value = this.minValue;
        }

        
        this.thumbs = [];

        Ext.slider.MultiSlider.superclass.initComponent.call(this);

        this.keyIncrement = Math.max(this.increment, this.keyIncrement);
        this.addEvents(
            
            'beforechange',

            
            'change',

            
            'changecomplete',

            
            'dragstart',

            
            'drag',

            
            'dragend'
        );

        
        if (this.values == undefined || Ext.isEmpty(this.values)) this.values = [0];

        var values = this.values;

        for (var i=0; i < values.length; i++) {
            this.addThumb(values[i]);
        }

        if(this.vertical){
            Ext.apply(this, Ext.slider.Vertical);
        }
    },

    
    addThumb: function(value) {
        var thumb = new Ext.slider.Thumb({
            value    : value,
            slider   : this,
            index    : this.thumbs.length,
            constrain: this.constrainThumbs
        });
        this.thumbs.push(thumb);

        
        if (this.rendered) thumb.render();
    },

    
    promoteThumb: function(topThumb) {
        var thumbs = this.thumbs,
            zIndex, thumb;

        for (var i = 0, j = thumbs.length; i < j; i++) {
            thumb = thumbs[i];

            if (thumb == topThumb) {
                zIndex = this.topThumbZIndex;
            } else {
                zIndex = '';
            }

            thumb.el.setStyle('zIndex', zIndex);
        }
    },

    
    onRender : function() {
        this.autoEl = {
            cls: 'x-slider ' + (this.vertical ? 'x-slider-vert' : 'x-slider-horz'),
            cn : {
                cls: 'x-slider-end',
                cn : {
                    cls:'x-slider-inner',
                    cn : [{tag:'a', cls:'x-slider-focus', href:"#", tabIndex: '-1', hidefocus:'on'}]
                }
            }
        };

        Ext.slider.MultiSlider.superclass.onRender.apply(this, arguments);

        this.endEl   = this.el.first();
        this.innerEl = this.endEl.first();
        this.focusEl = this.innerEl.child('.x-slider-focus');

        
        for (var i=0; i < this.thumbs.length; i++) {
            this.thumbs[i].render();
        }

        
        var thumb      = this.innerEl.child('.x-slider-thumb');
        this.halfThumb = (this.vertical ? thumb.getHeight() : thumb.getWidth()) / 2;

        this.initEvents();
    },

    
    initEvents : function(){
        this.mon(this.el, {
            scope    : this,
            mousedown: this.onMouseDown,
            keydown  : this.onKeyDown
        });

        this.focusEl.swallowEvent("click", true);
    },

    
    onMouseDown : function(e){
        if(this.disabled){
            return;
        }

        
        var thumbClicked = false;
        for (var i=0; i < this.thumbs.length; i++) {
            thumbClicked = thumbClicked || e.target == this.thumbs[i].el.dom;
        }

        if (this.clickToChange && !thumbClicked) {
            var local = this.innerEl.translatePoints(e.getXY());
            this.onClickChange(local);
        }
        this.focus();
    },

    
    onClickChange : function(local) {
        if (local.top > this.clickRange[0] && local.top < this.clickRange[1]) {
            
            var thumb = this.getNearest(local, 'left'),
                index = thumb.index;

            this.setValue(index, Ext.util.Format.round(this.reverseValue(local.left), this.decimalPrecision), undefined, true);
        }
    },

    
    getNearest: function(local, prop) {
        var localValue = prop == 'top' ? this.innerEl.getHeight() - local[prop] : local[prop],
            clickValue = this.reverseValue(localValue),
            nearestDistance = (this.maxValue - this.minValue) + 5, 
            index = 0,
            nearest = null;

        for (var i=0; i < this.thumbs.length; i++) {
            var thumb = this.thumbs[i],
                value = thumb.value,
                dist  = Math.abs(value - clickValue);

            if (Math.abs(dist <= nearestDistance)) {
                nearest = thumb;
                index = i;
                nearestDistance = dist;
            }
        }
        return nearest;
    },

    
    onKeyDown : function(e){
        
        if(this.disabled || this.thumbs.length !== 1){
            e.preventDefault();
            return;
        }
        var k = e.getKey(),
            val;
        switch(k){
            case e.UP:
            case e.RIGHT:
                e.stopEvent();
                val = e.ctrlKey ? this.maxValue : this.getValue(0) + this.keyIncrement;
                this.setValue(0, val, undefined, true);
            break;
            case e.DOWN:
            case e.LEFT:
                e.stopEvent();
                val = e.ctrlKey ? this.minValue : this.getValue(0) - this.keyIncrement;
                this.setValue(0, val, undefined, true);
            break;
            default:
                e.preventDefault();
        }
    },

    
    doSnap : function(value){
        if (!(this.increment && value)) {
            return value;
        }
        var newValue = value,
            inc = this.increment,
            m = value % inc;
        if (m != 0) {
            newValue -= m;
            if (m * 2 >= inc) {
                newValue += inc;
            } else if (m * 2 < -inc) {
                newValue -= inc;
            }
        }
        return newValue.constrain(this.minValue,  this.maxValue);
    },

    
    afterRender : function(){
        Ext.slider.MultiSlider.superclass.afterRender.apply(this, arguments);

        for (var i=0; i < this.thumbs.length; i++) {
            var thumb = this.thumbs[i];

            if (thumb.value !== undefined) {
                var v = this.normalizeValue(thumb.value);

                if (v !== thumb.value) {
                    
                    this.setValue(i, v, false);
                } else {
                    this.moveThumb(i, this.translateValue(v), false);
                }
            }
        };
    },

    
    getRatio : function(){
        var w = this.innerEl.getWidth(),
            v = this.maxValue - this.minValue;
        return v == 0 ? w : (w/v);
    },

    
    normalizeValue : function(v){
        v = this.doSnap(v);
        v = Ext.util.Format.round(v, this.decimalPrecision);
        v = v.constrain(this.minValue, this.maxValue);
        return v;
    },

    
    setMinValue : function(val){
        this.minValue = val;
        var i = 0,
            thumbs = this.thumbs,
            len = thumbs.length,
            t;
            
        for(; i < len; ++i){
            t = thumbs[i];
            t.value = t.value < val ? val : t.value;
        }
        this.syncThumb();
    },

    
    setMaxValue : function(val){
        this.maxValue = val;
        var i = 0,
            thumbs = this.thumbs,
            len = thumbs.length,
            t;
            
        for(; i < len; ++i){
            t = thumbs[i];
            t.value = t.value > val ? val : t.value;
        }
        this.syncThumb();
    },

    
    setValue : function(index, v, animate, changeComplete) {
        var thumb = this.thumbs[index],
            el    = thumb.el;

        v = this.normalizeValue(v);

        if (v !== thumb.value && this.fireEvent('beforechange', this, v, thumb.value, thumb) !== false) {
            thumb.value = v;
            if(this.rendered){
                this.moveThumb(index, this.translateValue(v), animate !== false);
                this.fireEvent('change', this, v, thumb);
                if(changeComplete){
                    this.fireEvent('changecomplete', this, v, thumb);
                }
            }
        }
    },

    
    translateValue : function(v) {
        var ratio = this.getRatio();
        return (v * ratio) - (this.minValue * ratio) - this.halfThumb;
    },

    
    reverseValue : function(pos){
        var ratio = this.getRatio();
        return (pos + (this.minValue * ratio)) / ratio;
    },

    
    moveThumb: function(index, v, animate){
        var thumb = this.thumbs[index].el;

        if(!animate || this.animate === false){
            thumb.setLeft(v);
        }else{
            thumb.shift({left: v, stopFx: true, duration:.35});
        }
    },

    
    focus : function(){
        this.focusEl.focus(10);
    },

    
    onResize : function(w, h){
        var thumbs = this.thumbs,
            len = thumbs.length,
            i = 0;
            
        
        for(; i < len; ++i){
            thumbs[i].el.stopFx();    
        }
        this.innerEl.setWidth(w - (this.el.getPadding('l') + this.endEl.getPadding('r')));
        this.syncThumb();
        Ext.slider.MultiSlider.superclass.onResize.apply(this, arguments);
    },

    
    onDisable: function(){
        Ext.slider.MultiSlider.superclass.onDisable.call(this);

        for (var i=0; i < this.thumbs.length; i++) {
            var thumb = this.thumbs[i],
                el    = thumb.el;

            thumb.disable();

            if(Ext.isIE){
                
                
                var xy = el.getXY();
                el.hide();

                this.innerEl.addClass(this.disabledClass).dom.disabled = true;

                if (!this.thumbHolder) {
                    this.thumbHolder = this.endEl.createChild({cls: 'x-slider-thumb ' + this.disabledClass});
                }

                this.thumbHolder.show().setXY(xy);
            }
        }
    },

    
    onEnable: function(){
        Ext.slider.MultiSlider.superclass.onEnable.call(this);

        for (var i=0; i < this.thumbs.length; i++) {
            var thumb = this.thumbs[i],
                el    = thumb.el;

            thumb.enable();

            if (Ext.isIE) {
                this.innerEl.removeClass(this.disabledClass).dom.disabled = false;

                if (this.thumbHolder) this.thumbHolder.hide();

                el.show();
                this.syncThumb();
            }
        }
    },

    
    syncThumb : function() {
        if (this.rendered) {
            for (var i=0; i < this.thumbs.length; i++) {
                this.moveThumb(i, this.translateValue(this.thumbs[i].value));
            }
        }
    },

    
    getValue : function(index) {
        return this.thumbs[index].value;
    },

    
    getValues: function() {
        var values = [];

        for (var i=0; i < this.thumbs.length; i++) {
            values.push(this.thumbs[i].value);
        }

        return values;
    },

    
    beforeDestroy : function(){
        Ext.destroyMembers(this, 'endEl', 'innerEl', 'thumb', 'halfThumb', 'focusEl', 'tracker', 'thumbHolder');
        Ext.slider.MultiSlider.superclass.beforeDestroy.call(this);
    }
});

Ext.reg('multislider', Ext.slider.MultiSlider);


Ext.slider.SingleSlider = Ext.extend(Ext.slider.MultiSlider, {
    constructor: function(config) {
      config = config || {};

      Ext.applyIf(config, {
          values: [config.value || 0]
      });

      Ext.slider.SingleSlider.superclass.constructor.call(this, config);
    },

    
    getValue: function() {
        
        return Ext.slider.SingleSlider.superclass.getValue.call(this, 0);
    },

    
    setValue: function(value, animate) {
        var args = Ext.toArray(arguments),
            len  = args.length;

        
        
        
        if (len == 1 || (len <= 3 && typeof arguments[1] != 'number')) {
            args.unshift(0);
        }

        return Ext.slider.SingleSlider.superclass.setValue.apply(this, args);
    },

    
    syncThumb : function() {
        return Ext.slider.SingleSlider.superclass.syncThumb.apply(this, [0].concat(arguments));
    },
    
    
    getNearest : function(){
        
        return this.thumbs[0];    
    }
});


Ext.Slider = Ext.slider.SingleSlider;

Ext.reg('slider', Ext.slider.SingleSlider);


Ext.slider.Vertical = {
    onResize : function(w, h){
        this.innerEl.setHeight(h - (this.el.getPadding('t') + this.endEl.getPadding('b')));
        this.syncThumb();
    },

    getRatio : function(){
        var h = this.innerEl.getHeight(),
            v = this.maxValue - this.minValue;
        return h/v;
    },

    moveThumb: function(index, v, animate) {
        var thumb = this.thumbs[index],
            el    = thumb.el;

        if (!animate || this.animate === false) {
            el.setBottom(v);
        } else {
            el.shift({bottom: v, stopFx: true, duration:.35});
        }
    },

    onClickChange : function(local) {
        if (local.left > this.clickRange[0] && local.left < this.clickRange[1]) {
            var thumb = this.getNearest(local, 'top'),
                index = thumb.index,
                value = this.minValue + this.reverseValue(this.innerEl.getHeight() - local.top);

            this.setValue(index, Ext.util.Format.round(value, this.decimalPrecision), undefined, true);
        }
    }
};


Ext.slider.Thumb.Vertical = {
    getNewValue: function() {
        var slider   = this.slider,
            innerEl  = slider.innerEl,
            pos      = innerEl.translatePoints(this.tracker.getXY()),
            bottom   = innerEl.getHeight() - pos.top;

        return slider.minValue + Ext.util.Format.round(bottom / slider.getRatio(), slider.decimalPrecision);
    }
};

Ext.ProgressBar = Ext.extend(Ext.BoxComponent, {
   
    baseCls : 'x-progress',
    
    
    animate : false,

    
    waitTimer : null,

    
    initComponent : function(){
        Ext.ProgressBar.superclass.initComponent.call(this);
        this.addEvents(
            
            "update"
        );
    },

    
    onRender : function(ct, position){
        var tpl = new Ext.Template(
            '<div class="{cls}-wrap">',
                '<div class="{cls}-inner">',
                    '<div class="{cls}-bar">',
                        '<div class="{cls}-text">',
                            '<div>&#160;</div>',
                        '</div>',
                    '</div>',
                    '<div class="{cls}-text {cls}-text-back">',
                        '<div>&#160;</div>',
                    '</div>',
                '</div>',
            '</div>'
        );

        this.el = position ? tpl.insertBefore(position, {cls: this.baseCls}, true)
            : tpl.append(ct, {cls: this.baseCls}, true);
                
        if(this.id){
            this.el.dom.id = this.id;
        }
        var inner = this.el.dom.firstChild;
        this.progressBar = Ext.get(inner.firstChild);

        if(this.textEl){
            
            this.textEl = Ext.get(this.textEl);
            delete this.textTopEl;
        }else{
            
            this.textTopEl = Ext.get(this.progressBar.dom.firstChild);
            var textBackEl = Ext.get(inner.childNodes[1]);
            this.textTopEl.setStyle("z-index", 99).addClass('x-hidden');
            this.textEl = new Ext.CompositeElement([this.textTopEl.dom.firstChild, textBackEl.dom.firstChild]);
            this.textEl.setWidth(inner.offsetWidth);
        }
        this.progressBar.setHeight(inner.offsetHeight);
    },
    
    
    afterRender : function(){
        Ext.ProgressBar.superclass.afterRender.call(this);
        if(this.value){
            this.updateProgress(this.value, this.text);
        }else{
            this.updateText(this.text);
        }
    },

    
    updateProgress : function(value, text, animate){
        this.value = value || 0;
        if(text){
            this.updateText(text);
        }
        if(this.rendered && !this.isDestroyed){
            var w = Math.floor(value*this.el.dom.firstChild.offsetWidth);
            this.progressBar.setWidth(w, animate === true || (animate !== false && this.animate));
            if(this.textTopEl){
                
                this.textTopEl.removeClass('x-hidden').setWidth(w);
            }
        }
        this.fireEvent('update', this, value, text);
        return this;
    },

    
    wait : function(o){
        if(!this.waitTimer){
            var scope = this;
            o = o || {};
            this.updateText(o.text);
            this.waitTimer = Ext.TaskMgr.start({
                run: function(i){
                    var inc = o.increment || 10;
                    i -= 1;
                    this.updateProgress(((((i+inc)%inc)+1)*(100/inc))*0.01, null, o.animate);
                },
                interval: o.interval || 1000,
                duration: o.duration,
                onStop: function(){
                    if(o.fn){
                        o.fn.apply(o.scope || this);
                    }
                    this.reset();
                },
                scope: scope
            });
        }
        return this;
    },

    
    isWaiting : function(){
        return this.waitTimer !== null;
    },

    
    updateText : function(text){
        this.text = text || '&#160;';
        if(this.rendered){
            this.textEl.update(this.text);
        }
        return this;
    },
    
    
    syncProgressBar : function(){
        if(this.value){
            this.updateProgress(this.value, this.text);
        }
        return this;
    },

    
    setSize : function(w, h){
        Ext.ProgressBar.superclass.setSize.call(this, w, h);
        if(this.textTopEl){
            var inner = this.el.dom.firstChild;
            this.textEl.setSize(inner.offsetWidth, inner.offsetHeight);
        }
        this.syncProgressBar();
        return this;
    },

    
    reset : function(hide){
        this.updateProgress(0);
        if(this.textTopEl){
            this.textTopEl.addClass('x-hidden');
        }
        this.clearTimer();
        if(hide === true){
            this.hide();
        }
        return this;
    },
    
    
    clearTimer : function(){
        if(this.waitTimer){
            this.waitTimer.onStop = null; 
            Ext.TaskMgr.stop(this.waitTimer);
            this.waitTimer = null;
        }
    },
    
    onDestroy: function(){
        this.clearTimer();
        if(this.rendered){
            if(this.textEl.isComposite){
                this.textEl.clear();
            }
            Ext.destroyMembers(this, 'textEl', 'progressBar', 'textTopEl');
        }
        Ext.ProgressBar.superclass.onDestroy.call(this);
    }
});
Ext.reg('progress', Ext.ProgressBar);

(function() {

var Event=Ext.EventManager;
var Dom=Ext.lib.Dom;


Ext.dd.DragDrop = function(id, sGroup, config) {
    if(id) {
        this.init(id, sGroup, config);
    }
};

Ext.dd.DragDrop.prototype = {

    

    
    id: null,

    
    config: null,

    
    dragElId: null,

    
    handleElId: null,

    
    invalidHandleTypes: null,

    
    invalidHandleIds: null,

    
    invalidHandleClasses: null,

    
    startPageX: 0,

    
    startPageY: 0,

    
    groups: null,

    
    locked: false,

    
    lock: function() {
        this.locked = true;
    },

    
    moveOnly: false,

    
    unlock: function() {
        this.locked = false;
    },

    
    isTarget: true,

    
    padding: null,

    
    _domRef: null,

    
    __ygDragDrop: true,

    
    constrainX: false,

    
    constrainY: false,

    
    minX: 0,

    
    maxX: 0,

    
    minY: 0,

    
    maxY: 0,

    
    maintainOffset: false,

    
    xTicks: null,

    
    yTicks: null,

    
    primaryButtonOnly: true,

    
    available: false,

    
    hasOuterHandles: false,

    
    b4StartDrag: function(x, y) { },

    
    startDrag: function(x, y) {  },

    
    b4Drag: function(e) { },

    
    onDrag: function(e) {  },

    
    onDragEnter: function(e, id) {  },

    
    b4DragOver: function(e) { },

    
    onDragOver: function(e, id) {  },

    
    b4DragOut: function(e) { },

    
    onDragOut: function(e, id) {  },

    
    b4DragDrop: function(e) { },

    
    onDragDrop: function(e, id) {  },

    
    onInvalidDrop: function(e) {  },

    
    b4EndDrag: function(e) { },

    
    endDrag: function(e) {  },

    
    b4MouseDown: function(e) {  },

    
    onMouseDown: function(e) {  },

    
    onMouseUp: function(e) {  },

    
    onAvailable: function () {
    },

    
    defaultPadding : {left:0, right:0, top:0, bottom:0},

    
    constrainTo : function(constrainTo, pad, inContent){
        if(Ext.isNumber(pad)){
            pad = {left: pad, right:pad, top:pad, bottom:pad};
        }
        pad = pad || this.defaultPadding;
        var b = Ext.get(this.getEl()).getBox(),
            ce = Ext.get(constrainTo),
            s = ce.getScroll(),
            c, 
            cd = ce.dom;
        if(cd == document.body){
            c = { x: s.left, y: s.top, width: Ext.lib.Dom.getViewWidth(), height: Ext.lib.Dom.getViewHeight()};
        }else{
            var xy = ce.getXY();
            c = {x : xy[0], y: xy[1], width: cd.clientWidth, height: cd.clientHeight};
        }


        var topSpace = b.y - c.y,
            leftSpace = b.x - c.x;

        this.resetConstraints();
        this.setXConstraint(leftSpace - (pad.left||0), 
                c.width - leftSpace - b.width - (pad.right||0), 
				this.xTickSize
        );
        this.setYConstraint(topSpace - (pad.top||0), 
                c.height - topSpace - b.height - (pad.bottom||0), 
				this.yTickSize
        );
    },

    
    getEl: function() {
        if (!this._domRef) {
            this._domRef = Ext.getDom(this.id);
        }

        return this._domRef;
    },

    
    getDragEl: function() {
        return Ext.getDom(this.dragElId);
    },

    
    init: function(id, sGroup, config) {
        this.initTarget(id, sGroup, config);
        Event.on(this.id, "mousedown", this.handleMouseDown, this);
        
    },

    
    initTarget: function(id, sGroup, config) {

        
        this.config = config || {};

        
        this.DDM = Ext.dd.DDM;
        
        this.groups = {};

        
        
        if (typeof id !== "string") {
            id = Ext.id(id);
        }

        
        this.id = id;

        
        this.addToGroup((sGroup) ? sGroup : "default");

        
        
        this.handleElId = id;

        
        this.setDragElId(id);

        
        this.invalidHandleTypes = { A: "A" };
        this.invalidHandleIds = {};
        this.invalidHandleClasses = [];

        this.applyConfig();

        this.handleOnAvailable();
    },

    
    applyConfig: function() {

        
        
        this.padding           = this.config.padding || [0, 0, 0, 0];
        this.isTarget          = (this.config.isTarget !== false);
        this.maintainOffset    = (this.config.maintainOffset);
        this.primaryButtonOnly = (this.config.primaryButtonOnly !== false);

    },

    
    handleOnAvailable: function() {
        this.available = true;
        this.resetConstraints();
        this.onAvailable();
    },

     
    setPadding: function(iTop, iRight, iBot, iLeft) {
        
        if (!iRight && 0 !== iRight) {
            this.padding = [iTop, iTop, iTop, iTop];
        } else if (!iBot && 0 !== iBot) {
            this.padding = [iTop, iRight, iTop, iRight];
        } else {
            this.padding = [iTop, iRight, iBot, iLeft];
        }
    },

    
    setInitPosition: function(diffX, diffY) {
        var el = this.getEl();

        if (!this.DDM.verifyEl(el)) {
            return;
        }

        var dx = diffX || 0;
        var dy = diffY || 0;

        var p = Dom.getXY( el );

        this.initPageX = p[0] - dx;
        this.initPageY = p[1] - dy;

        this.lastPageX = p[0];
        this.lastPageY = p[1];

        this.setStartPosition(p);
    },

    
    setStartPosition: function(pos) {
        var p = pos || Dom.getXY( this.getEl() );
        this.deltaSetXY = null;

        this.startPageX = p[0];
        this.startPageY = p[1];
    },

    
    addToGroup: function(sGroup) {
        this.groups[sGroup] = true;
        this.DDM.regDragDrop(this, sGroup);
    },

    
    removeFromGroup: function(sGroup) {
        if (this.groups[sGroup]) {
            delete this.groups[sGroup];
        }

        this.DDM.removeDDFromGroup(this, sGroup);
    },

    
    setDragElId: function(id) {
        this.dragElId = id;
    },

    
    setHandleElId: function(id) {
        if (typeof id !== "string") {
            id = Ext.id(id);
        }
        this.handleElId = id;
        this.DDM.regHandle(this.id, id);
    },

    
    setOuterHandleElId: function(id) {
        if (typeof id !== "string") {
            id = Ext.id(id);
        }
        Event.on(id, "mousedown",
                this.handleMouseDown, this);
        this.setHandleElId(id);

        this.hasOuterHandles = true;
    },

    
    unreg: function() {
        Event.un(this.id, "mousedown",
                this.handleMouseDown);
        this._domRef = null;
        this.DDM._remove(this);
    },

    destroy : function(){
        this.unreg();
    },

    
    isLocked: function() {
        return (this.DDM.isLocked() || this.locked);
    },

    
    handleMouseDown: function(e, oDD){
        if (this.primaryButtonOnly && e.button != 0) {
            return;
        }

        if (this.isLocked()) {
            return;
        }

        this.DDM.refreshCache(this.groups);

        var pt = new Ext.lib.Point(Ext.lib.Event.getPageX(e), Ext.lib.Event.getPageY(e));
        if (!this.hasOuterHandles && !this.DDM.isOverTarget(pt, this) )  {
        } else {
            if (this.clickValidator(e)) {

                
                this.setStartPosition();

                this.b4MouseDown(e);
                this.onMouseDown(e);

                this.DDM.handleMouseDown(e, this);

                this.DDM.stopEvent(e);
            } else {


            }
        }
    },

    clickValidator: function(e) {
        var target = e.getTarget();
        return ( this.isValidHandleChild(target) &&
                    (this.id == this.handleElId ||
                        this.DDM.handleWasClicked(target, this.id)) );
    },

    
    addInvalidHandleType: function(tagName) {
        var type = tagName.toUpperCase();
        this.invalidHandleTypes[type] = type;
    },

    
    addInvalidHandleId: function(id) {
        if (typeof id !== "string") {
            id = Ext.id(id);
        }
        this.invalidHandleIds[id] = id;
    },

    
    addInvalidHandleClass: function(cssClass) {
        this.invalidHandleClasses.push(cssClass);
    },

    
    removeInvalidHandleType: function(tagName) {
        var type = tagName.toUpperCase();
        
        delete this.invalidHandleTypes[type];
    },

    
    removeInvalidHandleId: function(id) {
        if (typeof id !== "string") {
            id = Ext.id(id);
        }
        delete this.invalidHandleIds[id];
    },

    
    removeInvalidHandleClass: function(cssClass) {
        for (var i=0, len=this.invalidHandleClasses.length; i<len; ++i) {
            if (this.invalidHandleClasses[i] == cssClass) {
                delete this.invalidHandleClasses[i];
            }
        }
    },

    
    isValidHandleChild: function(node) {

        var valid = true;
        
        var nodeName;
        try {
            nodeName = node.nodeName.toUpperCase();
        } catch(e) {
            nodeName = node.nodeName;
        }
        valid = valid && !this.invalidHandleTypes[nodeName];
        valid = valid && !this.invalidHandleIds[node.id];

        for (var i=0, len=this.invalidHandleClasses.length; valid && i<len; ++i) {
            valid = !Ext.fly(node).hasClass(this.invalidHandleClasses[i]);
        }


        return valid;

    },

    
    setXTicks: function(iStartX, iTickSize) {
        this.xTicks = [];
        this.xTickSize = iTickSize;

        var tickMap = {};

        for (var i = this.initPageX; i >= this.minX; i = i - iTickSize) {
            if (!tickMap[i]) {
                this.xTicks[this.xTicks.length] = i;
                tickMap[i] = true;
            }
        }

        for (i = this.initPageX; i <= this.maxX; i = i + iTickSize) {
            if (!tickMap[i]) {
                this.xTicks[this.xTicks.length] = i;
                tickMap[i] = true;
            }
        }

        this.xTicks.sort(this.DDM.numericSort) ;
    },

    
    setYTicks: function(iStartY, iTickSize) {
        this.yTicks = [];
        this.yTickSize = iTickSize;

        var tickMap = {};

        for (var i = this.initPageY; i >= this.minY; i = i - iTickSize) {
            if (!tickMap[i]) {
                this.yTicks[this.yTicks.length] = i;
                tickMap[i] = true;
            }
        }

        for (i = this.initPageY; i <= this.maxY; i = i + iTickSize) {
            if (!tickMap[i]) {
                this.yTicks[this.yTicks.length] = i;
                tickMap[i] = true;
            }
        }

        this.yTicks.sort(this.DDM.numericSort) ;
    },

    
    setXConstraint: function(iLeft, iRight, iTickSize) {
        this.leftConstraint = iLeft;
        this.rightConstraint = iRight;

        this.minX = this.initPageX - iLeft;
        this.maxX = this.initPageX + iRight;
        if (iTickSize) { this.setXTicks(this.initPageX, iTickSize); }

        this.constrainX = true;
    },

    
    clearConstraints: function() {
        this.constrainX = false;
        this.constrainY = false;
        this.clearTicks();
    },

    
    clearTicks: function() {
        this.xTicks = null;
        this.yTicks = null;
        this.xTickSize = 0;
        this.yTickSize = 0;
    },

    
    setYConstraint: function(iUp, iDown, iTickSize) {
        this.topConstraint = iUp;
        this.bottomConstraint = iDown;

        this.minY = this.initPageY - iUp;
        this.maxY = this.initPageY + iDown;
        if (iTickSize) { this.setYTicks(this.initPageY, iTickSize); }

        this.constrainY = true;

    },

    
    resetConstraints: function() {
        
        if (this.initPageX || this.initPageX === 0) {
            
            var dx = (this.maintainOffset) ? this.lastPageX - this.initPageX : 0;
            var dy = (this.maintainOffset) ? this.lastPageY - this.initPageY : 0;

            this.setInitPosition(dx, dy);

        
        } else {
            this.setInitPosition();
        }

        if (this.constrainX) {
            this.setXConstraint( this.leftConstraint,
                                 this.rightConstraint,
                                 this.xTickSize        );
        }

        if (this.constrainY) {
            this.setYConstraint( this.topConstraint,
                                 this.bottomConstraint,
                                 this.yTickSize         );
        }
    },

    
    getTick: function(val, tickArray) {
        if (!tickArray) {
            
            
            return val;
        } else if (tickArray[0] >= val) {
            
            
            return tickArray[0];
        } else {
            for (var i=0, len=tickArray.length; i<len; ++i) {
                var next = i + 1;
                if (tickArray[next] && tickArray[next] >= val) {
                    var diff1 = val - tickArray[i];
                    var diff2 = tickArray[next] - val;
                    return (diff2 > diff1) ? tickArray[i] : tickArray[next];
                }
            }

            
            
            return tickArray[tickArray.length - 1];
        }
    },

    
    toString: function() {
        return ("DragDrop " + this.id);
    }

};

})();




if (!Ext.dd.DragDropMgr) {


Ext.dd.DragDropMgr = function() {

    var Event = Ext.EventManager;

    return {

        
        ids: {},

        
        handleIds: {},

        
        dragCurrent: null,

        
        dragOvers: {},

        
        deltaX: 0,

        
        deltaY: 0,

        
        preventDefault: true,

        
        stopPropagation: true,

        
        initialized: false,

        
        locked: false,

        
        init: function() {
            this.initialized = true;
        },

        
        POINT: 0,

        
        INTERSECT: 1,

        
        mode: 0,

        
        _execOnAll: function(sMethod, args) {
            for (var i in this.ids) {
                for (var j in this.ids[i]) {
                    var oDD = this.ids[i][j];
                    if (! this.isTypeOfDD(oDD)) {
                        continue;
                    }
                    oDD[sMethod].apply(oDD, args);
                }
            }
        },

        
        _onLoad: function() {

            this.init();


            Event.on(document, "mouseup",   this.handleMouseUp, this, true);
            Event.on(document, "mousemove", this.handleMouseMove, this, true);
            Event.on(window,   "unload",    this._onUnload, this, true);
            Event.on(window,   "resize",    this._onResize, this, true);
            

        },

        
        _onResize: function(e) {
            this._execOnAll("resetConstraints", []);
        },

        
        lock: function() { this.locked = true; },

        
        unlock: function() { this.locked = false; },

        
        isLocked: function() { return this.locked; },

        
        locationCache: {},

        
        useCache: true,

        
        clickPixelThresh: 3,

        
        clickTimeThresh: 350,

        
        dragThreshMet: false,

        
        clickTimeout: null,

        
        startX: 0,

        
        startY: 0,

        
        regDragDrop: function(oDD, sGroup) {
            if (!this.initialized) { this.init(); }

            if (!this.ids[sGroup]) {
                this.ids[sGroup] = {};
            }
            this.ids[sGroup][oDD.id] = oDD;
        },

        
        removeDDFromGroup: function(oDD, sGroup) {
            if (!this.ids[sGroup]) {
                this.ids[sGroup] = {};
            }

            var obj = this.ids[sGroup];
            if (obj && obj[oDD.id]) {
                delete obj[oDD.id];
            }
        },

        
        _remove: function(oDD) {
            for (var g in oDD.groups) {
                if (g && this.ids[g] && this.ids[g][oDD.id]) {
                    delete this.ids[g][oDD.id];
                }
            }
            delete this.handleIds[oDD.id];
        },

        
        regHandle: function(sDDId, sHandleId) {
            if (!this.handleIds[sDDId]) {
                this.handleIds[sDDId] = {};
            }
            this.handleIds[sDDId][sHandleId] = sHandleId;
        },

        
        isDragDrop: function(id) {
            return ( this.getDDById(id) ) ? true : false;
        },

        
        getRelated: function(p_oDD, bTargetsOnly) {
            var oDDs = [];
            for (var i in p_oDD.groups) {
                for (var j in this.ids[i]) {
                    var dd = this.ids[i][j];
                    if (! this.isTypeOfDD(dd)) {
                        continue;
                    }
                    if (!bTargetsOnly || dd.isTarget) {
                        oDDs[oDDs.length] = dd;
                    }
                }
            }

            return oDDs;
        },

        
        isLegalTarget: function (oDD, oTargetDD) {
            var targets = this.getRelated(oDD, true);
            for (var i=0, len=targets.length;i<len;++i) {
                if (targets[i].id == oTargetDD.id) {
                    return true;
                }
            }

            return false;
        },

        
        isTypeOfDD: function (oDD) {
            return (oDD && oDD.__ygDragDrop);
        },

        
        isHandle: function(sDDId, sHandleId) {
            return ( this.handleIds[sDDId] &&
                            this.handleIds[sDDId][sHandleId] );
        },

        
        getDDById: function(id) {
            for (var i in this.ids) {
                if (this.ids[i][id]) {
                    return this.ids[i][id];
                }
            }
            return null;
        },

        
        handleMouseDown: function(e, oDD) {
            if(Ext.QuickTips){
                Ext.QuickTips.disable();
            }
            if(this.dragCurrent){
                
                
                this.handleMouseUp(e);
            }
            
            this.currentTarget = e.getTarget();
            this.dragCurrent = oDD;

            var el = oDD.getEl();

            
            this.startX = e.getPageX();
            this.startY = e.getPageY();

            this.deltaX = this.startX - el.offsetLeft;
            this.deltaY = this.startY - el.offsetTop;

            this.dragThreshMet = false;

            this.clickTimeout = setTimeout(
                    function() {
                        var DDM = Ext.dd.DDM;
                        DDM.startDrag(DDM.startX, DDM.startY);
                    },
                    this.clickTimeThresh );
        },

        
        startDrag: function(x, y) {
            clearTimeout(this.clickTimeout);
            if (this.dragCurrent) {
                this.dragCurrent.b4StartDrag(x, y);
                this.dragCurrent.startDrag(x, y);
            }
            this.dragThreshMet = true;
        },

        
        handleMouseUp: function(e) {

            if(Ext.QuickTips){
                Ext.QuickTips.enable();
            }
            if (! this.dragCurrent) {
                return;
            }

            clearTimeout(this.clickTimeout);

            if (this.dragThreshMet) {
                this.fireEvents(e, true);
            } else {
            }

            this.stopDrag(e);

            this.stopEvent(e);
        },

        
        stopEvent: function(e){
            if(this.stopPropagation) {
                e.stopPropagation();
            }

            if (this.preventDefault) {
                e.preventDefault();
            }
        },

        
        stopDrag: function(e) {
            
            if (this.dragCurrent) {
                if (this.dragThreshMet) {
                    this.dragCurrent.b4EndDrag(e);
                    this.dragCurrent.endDrag(e);
                }

                this.dragCurrent.onMouseUp(e);
            }

            this.dragCurrent = null;
            this.dragOvers = {};
        },

        
        handleMouseMove: function(e) {
            if (! this.dragCurrent) {
                return true;
            }
            

            
            if (Ext.isIE && (e.button !== 0 && e.button !== 1 && e.button !== 2)) {
                this.stopEvent(e);
                return this.handleMouseUp(e);
            }

            if (!this.dragThreshMet) {
                var diffX = Math.abs(this.startX - e.getPageX());
                var diffY = Math.abs(this.startY - e.getPageY());
                if (diffX > this.clickPixelThresh ||
                            diffY > this.clickPixelThresh) {
                    this.startDrag(this.startX, this.startY);
                }
            }

            if (this.dragThreshMet) {
                this.dragCurrent.b4Drag(e);
                this.dragCurrent.onDrag(e);
                if(!this.dragCurrent.moveOnly){
                    this.fireEvents(e, false);
                }
            }

            this.stopEvent(e);

            return true;
        },

        
        fireEvents: function(e, isDrop) {
            var dc = this.dragCurrent;

            
            
            if (!dc || dc.isLocked()) {
                return;
            }

            var pt = e.getPoint();

            
            var oldOvers = [];

            var outEvts   = [];
            var overEvts  = [];
            var dropEvts  = [];
            var enterEvts = [];

            
            
            for (var i in this.dragOvers) {

                var ddo = this.dragOvers[i];

                if (! this.isTypeOfDD(ddo)) {
                    continue;
                }

                if (! this.isOverTarget(pt, ddo, this.mode)) {
                    outEvts.push( ddo );
                }

                oldOvers[i] = true;
                delete this.dragOvers[i];
            }

            for (var sGroup in dc.groups) {

                if ("string" != typeof sGroup) {
                    continue;
                }

                for (i in this.ids[sGroup]) {
                    var oDD = this.ids[sGroup][i];
                    if (! this.isTypeOfDD(oDD)) {
                        continue;
                    }

                    if (oDD.isTarget && !oDD.isLocked() && ((oDD != dc) || (dc.ignoreSelf === false))) {
                        if (this.isOverTarget(pt, oDD, this.mode)) {
                            
                            if (isDrop) {
                                dropEvts.push( oDD );
                            
                            } else {

                                
                                if (!oldOvers[oDD.id]) {
                                    enterEvts.push( oDD );
                                
                                } else {
                                    overEvts.push( oDD );
                                }

                                this.dragOvers[oDD.id] = oDD;
                            }
                        }
                    }
                }
            }

            if (this.mode) {
                if (outEvts.length) {
                    dc.b4DragOut(e, outEvts);
                    dc.onDragOut(e, outEvts);
                }

                if (enterEvts.length) {
                    dc.onDragEnter(e, enterEvts);
                }

                if (overEvts.length) {
                    dc.b4DragOver(e, overEvts);
                    dc.onDragOver(e, overEvts);
                }

                if (dropEvts.length) {
                    dc.b4DragDrop(e, dropEvts);
                    dc.onDragDrop(e, dropEvts);
                }

            } else {
                
                var len = 0;
                for (i=0, len=outEvts.length; i<len; ++i) {
                    dc.b4DragOut(e, outEvts[i].id);
                    dc.onDragOut(e, outEvts[i].id);
                }

                
                for (i=0,len=enterEvts.length; i<len; ++i) {
                    
                    dc.onDragEnter(e, enterEvts[i].id);
                }

                
                for (i=0,len=overEvts.length; i<len; ++i) {
                    dc.b4DragOver(e, overEvts[i].id);
                    dc.onDragOver(e, overEvts[i].id);
                }

                
                for (i=0, len=dropEvts.length; i<len; ++i) {
                    dc.b4DragDrop(e, dropEvts[i].id);
                    dc.onDragDrop(e, dropEvts[i].id);
                }

            }

            
            if (isDrop && !dropEvts.length) {
                dc.onInvalidDrop(e);
            }

        },

        
        getBestMatch: function(dds) {
            var winner = null;
            
            
               
            
            

            var len = dds.length;

            if (len == 1) {
                winner = dds[0];
            } else {
                
                for (var i=0; i<len; ++i) {
                    var dd = dds[i];
                    
                    
                    
                    if (dd.cursorIsOver) {
                        winner = dd;
                        break;
                    
                    } else {
                        if (!winner ||
                            winner.overlap.getArea() < dd.overlap.getArea()) {
                            winner = dd;
                        }
                    }
                }
            }

            return winner;
        },

        
        refreshCache: function(groups) {
            for (var sGroup in groups) {
                if ("string" != typeof sGroup) {
                    continue;
                }
                for (var i in this.ids[sGroup]) {
                    var oDD = this.ids[sGroup][i];

                    if (this.isTypeOfDD(oDD)) {
                    
                        var loc = this.getLocation(oDD);
                        if (loc) {
                            this.locationCache[oDD.id] = loc;
                        } else {
                            delete this.locationCache[oDD.id];
                            
                            
                            
                        }
                    }
                }
            }
        },

        
        verifyEl: function(el) {
            if (el) {
                var parent;
                if(Ext.isIE){
                    try{
                        parent = el.offsetParent;
                    }catch(e){}
                }else{
                    parent = el.offsetParent;
                }
                if (parent) {
                    return true;
                }
            }

            return false;
        },

        
        getLocation: function(oDD) {
            if (! this.isTypeOfDD(oDD)) {
                return null;
            }

            var el = oDD.getEl(), pos, x1, x2, y1, y2, t, r, b, l;

            try {
                pos= Ext.lib.Dom.getXY(el);
            } catch (e) { }

            if (!pos) {
                return null;
            }

            x1 = pos[0];
            x2 = x1 + el.offsetWidth;
            y1 = pos[1];
            y2 = y1 + el.offsetHeight;

            t = y1 - oDD.padding[0];
            r = x2 + oDD.padding[1];
            b = y2 + oDD.padding[2];
            l = x1 - oDD.padding[3];

            return new Ext.lib.Region( t, r, b, l );
        },

        
        isOverTarget: function(pt, oTarget, intersect) {
            
            var loc = this.locationCache[oTarget.id];
            if (!loc || !this.useCache) {
                loc = this.getLocation(oTarget);
                this.locationCache[oTarget.id] = loc;

            }

            if (!loc) {
                return false;
            }

            oTarget.cursorIsOver = loc.contains( pt );

            
            
            
            
            
            var dc = this.dragCurrent;
            if (!dc || !dc.getTargetCoord ||
                    (!intersect && !dc.constrainX && !dc.constrainY)) {
                return oTarget.cursorIsOver;
            }

            oTarget.overlap = null;

            
            
            
            
            var pos = dc.getTargetCoord(pt.x, pt.y);

            var el = dc.getDragEl();
            var curRegion = new Ext.lib.Region( pos.y,
                                                   pos.x + el.offsetWidth,
                                                   pos.y + el.offsetHeight,
                                                   pos.x );

            var overlap = curRegion.intersect(loc);

            if (overlap) {
                oTarget.overlap = overlap;
                return (intersect) ? true : oTarget.cursorIsOver;
            } else {
                return false;
            }
        },

        
        _onUnload: function(e, me) {
            Ext.dd.DragDropMgr.unregAll();
        },

        
        unregAll: function() {

            if (this.dragCurrent) {
                this.stopDrag();
                this.dragCurrent = null;
            }

            this._execOnAll("unreg", []);

            for (var i in this.elementCache) {
                delete this.elementCache[i];
            }

            this.elementCache = {};
            this.ids = {};
        },

        
        elementCache: {},

        
        getElWrapper: function(id) {
            var oWrapper = this.elementCache[id];
            if (!oWrapper || !oWrapper.el) {
                oWrapper = this.elementCache[id] =
                    new this.ElementWrapper(Ext.getDom(id));
            }
            return oWrapper;
        },

        
        getElement: function(id) {
            return Ext.getDom(id);
        },

        
        getCss: function(id) {
            var el = Ext.getDom(id);
            return (el) ? el.style : null;
        },

        
        ElementWrapper: function(el) {
                
                this.el = el || null;
                
                this.id = this.el && el.id;
                
                this.css = this.el && el.style;
            },

        
        getPosX: function(el) {
            return Ext.lib.Dom.getX(el);
        },

        
        getPosY: function(el) {
            return Ext.lib.Dom.getY(el);
        },

        
        swapNode: function(n1, n2) {
            if (n1.swapNode) {
                n1.swapNode(n2);
            } else {
                var p = n2.parentNode;
                var s = n2.nextSibling;

                if (s == n1) {
                    p.insertBefore(n1, n2);
                } else if (n2 == n1.nextSibling) {
                    p.insertBefore(n2, n1);
                } else {
                    n1.parentNode.replaceChild(n2, n1);
                    p.insertBefore(n1, s);
                }
            }
        },

        
        getScroll: function () {
            var t, l, dde=document.documentElement, db=document.body;
            if (dde && (dde.scrollTop || dde.scrollLeft)) {
                t = dde.scrollTop;
                l = dde.scrollLeft;
            } else if (db) {
                t = db.scrollTop;
                l = db.scrollLeft;
            } else {

            }
            return { top: t, left: l };
        },

        
        getStyle: function(el, styleProp) {
            return Ext.fly(el).getStyle(styleProp);
        },

        
        getScrollTop: function () {
            return this.getScroll().top;
        },

        
        getScrollLeft: function () {
            return this.getScroll().left;
        },

        
        moveToEl: function (moveEl, targetEl) {
            var aCoord = Ext.lib.Dom.getXY(targetEl);
            Ext.lib.Dom.setXY(moveEl, aCoord);
        },

        
        numericSort: function(a, b) {
            return (a - b);
        },

        
        _timeoutCount: 0,

        
        _addListeners: function() {
            var DDM = Ext.dd.DDM;
            if ( Ext.lib.Event && document ) {
                DDM._onLoad();
            } else {
                if (DDM._timeoutCount > 2000) {
                } else {
                    setTimeout(DDM._addListeners, 10);
                    if (document && document.body) {
                        DDM._timeoutCount += 1;
                    }
                }
            }
        },

        
        handleWasClicked: function(node, id) {
            if (this.isHandle(id, node.id)) {
                return true;
            } else {
                
                var p = node.parentNode;

                while (p) {
                    if (this.isHandle(id, p.id)) {
                        return true;
                    } else {
                        p = p.parentNode;
                    }
                }
            }

            return false;
        }

    };

}();


Ext.dd.DDM = Ext.dd.DragDropMgr;
Ext.dd.DDM._addListeners();

}


Ext.dd.DD = function(id, sGroup, config) {
    if (id) {
        this.init(id, sGroup, config);
    }
};

Ext.extend(Ext.dd.DD, Ext.dd.DragDrop, {

    
    scroll: true,

    
    autoOffset: function(iPageX, iPageY) {
        var x = iPageX - this.startPageX;
        var y = iPageY - this.startPageY;
        this.setDelta(x, y);
    },

    
    setDelta: function(iDeltaX, iDeltaY) {
        this.deltaX = iDeltaX;
        this.deltaY = iDeltaY;
    },

    
    setDragElPos: function(iPageX, iPageY) {
        
        

        var el = this.getDragEl();
        this.alignElWithMouse(el, iPageX, iPageY);
    },

    
    alignElWithMouse: function(el, iPageX, iPageY) {
        var oCoord = this.getTargetCoord(iPageX, iPageY);
        var fly = el.dom ? el : Ext.fly(el, '_dd');
        if (!this.deltaSetXY) {
            var aCoord = [oCoord.x, oCoord.y];
            fly.setXY(aCoord);
            var newLeft = fly.getLeft(true);
            var newTop  = fly.getTop(true);
            this.deltaSetXY = [ newLeft - oCoord.x, newTop - oCoord.y ];
        } else {
            fly.setLeftTop(oCoord.x + this.deltaSetXY[0], oCoord.y + this.deltaSetXY[1]);
        }

        this.cachePosition(oCoord.x, oCoord.y);
        this.autoScroll(oCoord.x, oCoord.y, el.offsetHeight, el.offsetWidth);
        return oCoord;
    },

    
    cachePosition: function(iPageX, iPageY) {
        if (iPageX) {
            this.lastPageX = iPageX;
            this.lastPageY = iPageY;
        } else {
            var aCoord = Ext.lib.Dom.getXY(this.getEl());
            this.lastPageX = aCoord[0];
            this.lastPageY = aCoord[1];
        }
    },

    
    autoScroll: function(x, y, h, w) {

        if (this.scroll) {
            
            var clientH = Ext.lib.Dom.getViewHeight();

            
            var clientW = Ext.lib.Dom.getViewWidth();

            
            var st = this.DDM.getScrollTop();

            
            var sl = this.DDM.getScrollLeft();

            
            var bot = h + y;

            
            var right = w + x;

            
            
            
            var toBot = (clientH + st - y - this.deltaY);

            
            var toRight = (clientW + sl - x - this.deltaX);


            
            
            var thresh = 40;

            
            
            
            var scrAmt = (document.all) ? 80 : 30;

            
            
            if ( bot > clientH && toBot < thresh ) {
                window.scrollTo(sl, st + scrAmt);
            }

            
            
            if ( y < st && st > 0 && y - st < thresh ) {
                window.scrollTo(sl, st - scrAmt);
            }

            
            
            if ( right > clientW && toRight < thresh ) {
                window.scrollTo(sl + scrAmt, st);
            }

            
            
            if ( x < sl && sl > 0 && x - sl < thresh ) {
                window.scrollTo(sl - scrAmt, st);
            }
        }
    },

    
    getTargetCoord: function(iPageX, iPageY) {
        var x = iPageX - this.deltaX;
        var y = iPageY - this.deltaY;

        if (this.constrainX) {
            if (x < this.minX) { x = this.minX; }
            if (x > this.maxX) { x = this.maxX; }
        }

        if (this.constrainY) {
            if (y < this.minY) { y = this.minY; }
            if (y > this.maxY) { y = this.maxY; }
        }

        x = this.getTick(x, this.xTicks);
        y = this.getTick(y, this.yTicks);


        return {x:x, y:y};
    },

    
    applyConfig: function() {
        Ext.dd.DD.superclass.applyConfig.call(this);
        this.scroll = (this.config.scroll !== false);
    },

    
    b4MouseDown: function(e) {
        
        this.autoOffset(e.getPageX(),
                            e.getPageY());
    },

    
    b4Drag: function(e) {
        this.setDragElPos(e.getPageX(),
                            e.getPageY());
    },

    toString: function() {
        return ("DD " + this.id);
    }

    
    
    
    

});

Ext.dd.DDProxy = function(id, sGroup, config) {
    if (id) {
        this.init(id, sGroup, config);
        this.initFrame();
    }
};


Ext.dd.DDProxy.dragElId = "ygddfdiv";

Ext.extend(Ext.dd.DDProxy, Ext.dd.DD, {

    
    resizeFrame: true,

    
    centerFrame: false,

    
    createFrame: function() {
        var self = this;
        var body = document.body;

        if (!body || !body.firstChild) {
            setTimeout( function() { self.createFrame(); }, 50 );
            return;
        }

        var div = this.getDragEl();

        if (!div) {
            div    = document.createElement("div");
            div.id = this.dragElId;
            var s  = div.style;

            s.position   = "absolute";
            s.visibility = "hidden";
            s.cursor     = "move";
            s.border     = "2px solid #aaa";
            s.zIndex     = 999;

            
            
            
            body.insertBefore(div, body.firstChild);
        }
    },

    
    initFrame: function() {
        this.createFrame();
    },

    applyConfig: function() {
        Ext.dd.DDProxy.superclass.applyConfig.call(this);

        this.resizeFrame = (this.config.resizeFrame !== false);
        this.centerFrame = (this.config.centerFrame);
        this.setDragElId(this.config.dragElId || Ext.dd.DDProxy.dragElId);
    },

    
    showFrame: function(iPageX, iPageY) {
        var el = this.getEl();
        var dragEl = this.getDragEl();
        var s = dragEl.style;

        this._resizeProxy();

        if (this.centerFrame) {
            this.setDelta( Math.round(parseInt(s.width,  10)/2),
                           Math.round(parseInt(s.height, 10)/2) );
        }

        this.setDragElPos(iPageX, iPageY);

        Ext.fly(dragEl).show();
    },

    
    _resizeProxy: function() {
        if (this.resizeFrame) {
            var el = this.getEl();
            Ext.fly(this.getDragEl()).setSize(el.offsetWidth, el.offsetHeight);
        }
    },

    
    b4MouseDown: function(e) {
        var x = e.getPageX();
        var y = e.getPageY();
        this.autoOffset(x, y);
        this.setDragElPos(x, y);
    },

    
    b4StartDrag: function(x, y) {
        
        this.showFrame(x, y);
    },

    
    b4EndDrag: function(e) {
        Ext.fly(this.getDragEl()).hide();
    },

    
    
    
    endDrag: function(e) {

        var lel = this.getEl();
        var del = this.getDragEl();

        
        del.style.visibility = "";

        this.beforeMove();
        
        
        lel.style.visibility = "hidden";
        Ext.dd.DDM.moveToEl(lel, del);
        del.style.visibility = "hidden";
        lel.style.visibility = "";

        this.afterDrag();
    },

    beforeMove : function(){

    },

    afterDrag : function(){

    },

    toString: function() {
        return ("DDProxy " + this.id);
    }

});

Ext.dd.DDTarget = function(id, sGroup, config) {
    if (id) {
        this.initTarget(id, sGroup, config);
    }
};


Ext.extend(Ext.dd.DDTarget, Ext.dd.DragDrop, {
    
    getDragEl: Ext.emptyFn,
    
    isValidHandleChild: Ext.emptyFn,
    
    startDrag: Ext.emptyFn,
    
    endDrag: Ext.emptyFn,
    
    onDrag: Ext.emptyFn,
    
    onDragDrop: Ext.emptyFn,
    
    onDragEnter: Ext.emptyFn,
    
    onDragOut: Ext.emptyFn,
    
    onDragOver: Ext.emptyFn,
    
    onInvalidDrop: Ext.emptyFn,
    
    onMouseDown: Ext.emptyFn,
    
    onMouseUp: Ext.emptyFn,
    
    setXConstraint: Ext.emptyFn,
    
    setYConstraint: Ext.emptyFn,
    
    resetConstraints: Ext.emptyFn,
    
    clearConstraints: Ext.emptyFn,
    
    clearTicks: Ext.emptyFn,
    
    setInitPosition: Ext.emptyFn,
    
    setDragElId: Ext.emptyFn,
    
    setHandleElId: Ext.emptyFn,
    
    setOuterHandleElId: Ext.emptyFn,
    
    addInvalidHandleClass: Ext.emptyFn,
    
    addInvalidHandleId: Ext.emptyFn,
    
    addInvalidHandleType: Ext.emptyFn,
    
    removeInvalidHandleClass: Ext.emptyFn,
    
    removeInvalidHandleId: Ext.emptyFn,
    
    removeInvalidHandleType: Ext.emptyFn,

    toString: function() {
        return ("DDTarget " + this.id);
    }
});
Ext.dd.DragTracker = Ext.extend(Ext.util.Observable,  {    
    	
    active: false,
    	
    tolerance: 5,
    	
    autoStart: false,
    
    constructor : function(config){
        Ext.apply(this, config);
	    this.addEvents(
	        
	        'mousedown',
	        
	        'mouseup',
	        
	        'mousemove',
	        
	        'dragstart',
	        
	        'dragend',
	        
	        'drag'
	    );
	
	    this.dragRegion = new Ext.lib.Region(0,0,0,0);
	
	    if(this.el){
	        this.initEl(this.el);
	    }
        Ext.dd.DragTracker.superclass.constructor.call(this, config);
    },

    initEl: function(el){
        this.el = Ext.get(el);
        el.on('mousedown', this.onMouseDown, this,
                this.delegate ? {delegate: this.delegate} : undefined);
    },

    destroy : function(){
        this.el.un('mousedown', this.onMouseDown, this);
    },

    onMouseDown: function(e, target){
        if(this.fireEvent('mousedown', this, e) !== false && this.onBeforeStart(e) !== false){
            this.startXY = this.lastXY = e.getXY();
            this.dragTarget = this.delegate ? target : this.el.dom;
            if(this.preventDefault !== false){
                e.preventDefault();
            }
            var doc = Ext.getDoc();
            doc.on('mouseup', this.onMouseUp, this);
            doc.on('mousemove', this.onMouseMove, this);
            doc.on('selectstart', this.stopSelect, this);
            if(this.autoStart){
                this.timer = this.triggerStart.defer(this.autoStart === true ? 1000 : this.autoStart, this);
            }
        }
    },

    onMouseMove: function(e, target){
        
        if(this.active && Ext.isIE && !e.browserEvent.button){
            e.preventDefault();
            this.onMouseUp(e);
            return;
        }

        e.preventDefault();
        var xy = e.getXY(), s = this.startXY;
        this.lastXY = xy;
        if(!this.active){
            if(Math.abs(s[0]-xy[0]) > this.tolerance || Math.abs(s[1]-xy[1]) > this.tolerance){
                this.triggerStart();
            }else{
                return;
            }
        }
        this.fireEvent('mousemove', this, e);
        this.onDrag(e);
        this.fireEvent('drag', this, e);
    },

    onMouseUp: function(e) {
        var doc = Ext.getDoc();
        doc.un('mousemove', this.onMouseMove, this);
        doc.un('mouseup', this.onMouseUp, this);
        doc.un('selectstart', this.stopSelect, this);
        e.preventDefault();
        this.clearStart();
        var wasActive = this.active;
        this.active = false;
        delete this.elRegion;
        this.fireEvent('mouseup', this, e);
        if(wasActive){
            this.onEnd(e);
            this.fireEvent('dragend', this, e);
        }
    },

    triggerStart: function(isTimer) {
        this.clearStart();
        this.active = true;
        this.onStart(this.startXY);
        this.fireEvent('dragstart', this, this.startXY);
    },

    clearStart : function() {
        if(this.timer){
            clearTimeout(this.timer);
            delete this.timer;
        }
    },

    stopSelect : function(e) {
        e.stopEvent();
        return false;
    },
    
    
    onBeforeStart : function(e) {

    },

    
    onStart : function(xy) {

    },

    
    onDrag : function(e) {

    },

    
    onEnd : function(e) {

    },

    
    getDragTarget : function(){
        return this.dragTarget;
    },

    getDragCt : function(){
        return this.el;
    },

    getXY : function(constrain){
        return constrain ?
               this.constrainModes[constrain].call(this, this.lastXY) : this.lastXY;
    },

    getOffset : function(constrain){
        var xy = this.getXY(constrain);
        var s = this.startXY;
        return [s[0]-xy[0], s[1]-xy[1]];
    },

    constrainModes: {
        'point' : function(xy){

            if(!this.elRegion){
                this.elRegion = this.getDragCt().getRegion();
            }

            var dr = this.dragRegion;

            dr.left = xy[0];
            dr.top = xy[1];
            dr.right = xy[0];
            dr.bottom = xy[1];

            dr.constrainTo(this.elRegion);

            return [dr.left, dr.top];
        }
    }
});
Ext.dd.ScrollManager = function(){
    var ddm = Ext.dd.DragDropMgr;
    var els = {};
    var dragEl = null;
    var proc = {};
    
    var onStop = function(e){
        dragEl = null;
        clearProc();
    };
    
    var triggerRefresh = function(){
        if(ddm.dragCurrent){
             ddm.refreshCache(ddm.dragCurrent.groups);
        }
    };
    
    var doScroll = function(){
        if(ddm.dragCurrent){
            var dds = Ext.dd.ScrollManager;
            var inc = proc.el.ddScrollConfig ?
                      proc.el.ddScrollConfig.increment : dds.increment;
            if(!dds.animate){
                if(proc.el.scroll(proc.dir, inc)){
                    triggerRefresh();
                }
            }else{
                proc.el.scroll(proc.dir, inc, true, dds.animDuration, triggerRefresh);
            }
        }
    };
    
    var clearProc = function(){
        if(proc.id){
            clearInterval(proc.id);
        }
        proc.id = 0;
        proc.el = null;
        proc.dir = "";
    };
    
    var startProc = function(el, dir){
        clearProc();
        proc.el = el;
        proc.dir = dir;
        var freq = (el.ddScrollConfig && el.ddScrollConfig.frequency) ? 
                el.ddScrollConfig.frequency : Ext.dd.ScrollManager.frequency;
        proc.id = setInterval(doScroll, freq);
    };
    
    var onFire = function(e, isDrop){
        if(isDrop || !ddm.dragCurrent){ return; }
        var dds = Ext.dd.ScrollManager;
        if(!dragEl || dragEl != ddm.dragCurrent){
            dragEl = ddm.dragCurrent;
            
            dds.refreshCache();
        }
        
        var xy = Ext.lib.Event.getXY(e);
        var pt = new Ext.lib.Point(xy[0], xy[1]);
        for(var id in els){
            var el = els[id], r = el._region;
            var c = el.ddScrollConfig ? el.ddScrollConfig : dds;
            if(r && r.contains(pt) && el.isScrollable()){
                if(r.bottom - pt.y <= c.vthresh){
                    if(proc.el != el){
                        startProc(el, "down");
                    }
                    return;
                }else if(r.right - pt.x <= c.hthresh){
                    if(proc.el != el){
                        startProc(el, "left");
                    }
                    return;
                }else if(pt.y - r.top <= c.vthresh){
                    if(proc.el != el){
                        startProc(el, "up");
                    }
                    return;
                }else if(pt.x - r.left <= c.hthresh){
                    if(proc.el != el){
                        startProc(el, "right");
                    }
                    return;
                }
            }
        }
        clearProc();
    };
    
    ddm.fireEvents = ddm.fireEvents.createSequence(onFire, ddm);
    ddm.stopDrag = ddm.stopDrag.createSequence(onStop, ddm);
    
    return {
        
        register : function(el){
            if(Ext.isArray(el)){
                for(var i = 0, len = el.length; i < len; i++) {
                	this.register(el[i]);
                }
            }else{
                el = Ext.get(el);
                els[el.id] = el;
            }
        },
        
        
        unregister : function(el){
            if(Ext.isArray(el)){
                for(var i = 0, len = el.length; i < len; i++) {
                	this.unregister(el[i]);
                }
            }else{
                el = Ext.get(el);
                delete els[el.id];
            }
        },
        
        
        vthresh : 25,
        
        hthresh : 25,

        
        increment : 100,
        
        
        frequency : 500,
        
        
        animate: true,
        
        
        animDuration: .4,
        
        
        refreshCache : function(){
            for(var id in els){
                if(typeof els[id] == 'object'){ 
                    els[id]._region = els[id].getRegion();
                }
            }
        }
    };
}();
Ext.dd.Registry = function(){
    var elements = {}; 
    var handles = {}; 
    var autoIdSeed = 0;

    var getId = function(el, autogen){
        if(typeof el == "string"){
            return el;
        }
        var id = el.id;
        if(!id && autogen !== false){
            id = "extdd-" + (++autoIdSeed);
            el.id = id;
        }
        return id;
    };
    
    return {
    
        register : function(el, data){
            data = data || {};
            if(typeof el == "string"){
                el = document.getElementById(el);
            }
            data.ddel = el;
            elements[getId(el)] = data;
            if(data.isHandle !== false){
                handles[data.ddel.id] = data;
            }
            if(data.handles){
                var hs = data.handles;
                for(var i = 0, len = hs.length; i < len; i++){
                	handles[getId(hs[i])] = data;
                }
            }
        },

    
        unregister : function(el){
            var id = getId(el, false);
            var data = elements[id];
            if(data){
                delete elements[id];
                if(data.handles){
                    var hs = data.handles;
                    for(var i = 0, len = hs.length; i < len; i++){
                    	delete handles[getId(hs[i], false)];
                    }
                }
            }
        },

    
        getHandle : function(id){
            if(typeof id != "string"){ 
                id = id.id;
            }
            return handles[id];
        },

    
        getHandleFromEvent : function(e){
            var t = Ext.lib.Event.getTarget(e);
            return t ? handles[t.id] : null;
        },

    
        getTarget : function(id){
            if(typeof id != "string"){ 
                id = id.id;
            }
            return elements[id];
        },

    
        getTargetFromEvent : function(e){
            var t = Ext.lib.Event.getTarget(e);
            return t ? elements[t.id] || handles[t.id] : null;
        }
    };
}();
Ext.dd.StatusProxy = function(config){
    Ext.apply(this, config);
    this.id = this.id || Ext.id();
    this.el = new Ext.Layer({
        dh: {
            id: this.id, tag: "div", cls: "x-dd-drag-proxy "+this.dropNotAllowed, children: [
                {tag: "div", cls: "x-dd-drop-icon"},
                {tag: "div", cls: "x-dd-drag-ghost"}
            ]
        }, 
        shadow: !config || config.shadow !== false
    });
    this.ghost = Ext.get(this.el.dom.childNodes[1]);
    this.dropStatus = this.dropNotAllowed;
};

Ext.dd.StatusProxy.prototype = {
    
    dropAllowed : "x-dd-drop-ok",
    
    dropNotAllowed : "x-dd-drop-nodrop",

    
    setStatus : function(cssClass){
        cssClass = cssClass || this.dropNotAllowed;
        if(this.dropStatus != cssClass){
            this.el.replaceClass(this.dropStatus, cssClass);
            this.dropStatus = cssClass;
        }
    },

    
    reset : function(clearGhost){
        this.el.dom.className = "x-dd-drag-proxy " + this.dropNotAllowed;
        this.dropStatus = this.dropNotAllowed;
        if(clearGhost){
            this.ghost.update("");
        }
    },

    
    update : function(html){
        if(typeof html == "string"){
            this.ghost.update(html);
        }else{
            this.ghost.update("");
            html.style.margin = "0";
            this.ghost.dom.appendChild(html);
        }
        var el = this.ghost.dom.firstChild; 
        if(el){
            Ext.fly(el).setStyle('float', 'none');
        }
    },

    
    getEl : function(){
        return this.el;
    },

    
    getGhost : function(){
        return this.ghost;
    },

    
    hide : function(clear){
        this.el.hide();
        if(clear){
            this.reset(true);
        }
    },

    
    stop : function(){
        if(this.anim && this.anim.isAnimated && this.anim.isAnimated()){
            this.anim.stop();
        }
    },

    
    show : function(){
        this.el.show();
    },

    
    sync : function(){
        this.el.sync();
    },

    
    repair : function(xy, callback, scope){
        this.callback = callback;
        this.scope = scope;
        if(xy && this.animRepair !== false){
            this.el.addClass("x-dd-drag-repair");
            this.el.hideUnders(true);
            this.anim = this.el.shift({
                duration: this.repairDuration || .5,
                easing: 'easeOut',
                xy: xy,
                stopFx: true,
                callback: this.afterRepair,
                scope: this
            });
        }else{
            this.afterRepair();
        }
    },

    
    afterRepair : function(){
        this.hide(true);
        if(typeof this.callback == "function"){
            this.callback.call(this.scope || this);
        }
        this.callback = null;
        this.scope = null;
    },
    
    destroy: function(){
        Ext.destroy(this.ghost, this.el);    
    }
};
Ext.dd.DragSource = function(el, config){
    this.el = Ext.get(el);
    if(!this.dragData){
        this.dragData = {};
    }
    
    Ext.apply(this, config);
    
    if(!this.proxy){
        this.proxy = new Ext.dd.StatusProxy();
    }
    Ext.dd.DragSource.superclass.constructor.call(this, this.el.dom, this.ddGroup || this.group, 
          {dragElId : this.proxy.id, resizeFrame: false, isTarget: false, scroll: this.scroll === true});
    
    this.dragging = false;
};

Ext.extend(Ext.dd.DragSource, Ext.dd.DDProxy, {
    
    
    dropAllowed : "x-dd-drop-ok",
    
    dropNotAllowed : "x-dd-drop-nodrop",

    
    getDragData : function(e){
        return this.dragData;
    },

    
    onDragEnter : function(e, id){
        var target = Ext.dd.DragDropMgr.getDDById(id);
        this.cachedTarget = target;
        if(this.beforeDragEnter(target, e, id) !== false){
            if(target.isNotifyTarget){
                var status = target.notifyEnter(this, e, this.dragData);
                this.proxy.setStatus(status);
            }else{
                this.proxy.setStatus(this.dropAllowed);
            }
            
            if(this.afterDragEnter){
                
                this.afterDragEnter(target, e, id);
            }
        }
    },

    
    beforeDragEnter : function(target, e, id){
        return true;
    },

    
    alignElWithMouse: function() {
        Ext.dd.DragSource.superclass.alignElWithMouse.apply(this, arguments);
        this.proxy.sync();
    },

    
    onDragOver : function(e, id){
        var target = this.cachedTarget || Ext.dd.DragDropMgr.getDDById(id);
        if(this.beforeDragOver(target, e, id) !== false){
            if(target.isNotifyTarget){
                var status = target.notifyOver(this, e, this.dragData);
                this.proxy.setStatus(status);
            }

            if(this.afterDragOver){
                
                this.afterDragOver(target, e, id);
            }
        }
    },

    
    beforeDragOver : function(target, e, id){
        return true;
    },

    
    onDragOut : function(e, id){
        var target = this.cachedTarget || Ext.dd.DragDropMgr.getDDById(id);
        if(this.beforeDragOut(target, e, id) !== false){
            if(target.isNotifyTarget){
                target.notifyOut(this, e, this.dragData);
            }
            this.proxy.reset();
            if(this.afterDragOut){
                
                this.afterDragOut(target, e, id);
            }
        }
        this.cachedTarget = null;
    },

    
    beforeDragOut : function(target, e, id){
        return true;
    },
    
    
    onDragDrop : function(e, id){
        var target = this.cachedTarget || Ext.dd.DragDropMgr.getDDById(id);
        if(this.beforeDragDrop(target, e, id) !== false){
            if(target.isNotifyTarget){
                if(target.notifyDrop(this, e, this.dragData)){ 
                    this.onValidDrop(target, e, id);
                }else{
                    this.onInvalidDrop(target, e, id);
                }
            }else{
                this.onValidDrop(target, e, id);
            }
            
            if(this.afterDragDrop){
                
                this.afterDragDrop(target, e, id);
            }
        }
        delete this.cachedTarget;
    },

    
    beforeDragDrop : function(target, e, id){
        return true;
    },

    
    onValidDrop : function(target, e, id){
        this.hideProxy();
        if(this.afterValidDrop){
            
            this.afterValidDrop(target, e, id);
        }
    },

    
    getRepairXY : function(e, data){
        return this.el.getXY();  
    },

    
    onInvalidDrop : function(target, e, id){
        this.beforeInvalidDrop(target, e, id);
        if(this.cachedTarget){
            if(this.cachedTarget.isNotifyTarget){
                this.cachedTarget.notifyOut(this, e, this.dragData);
            }
            this.cacheTarget = null;
        }
        this.proxy.repair(this.getRepairXY(e, this.dragData), this.afterRepair, this);

        if(this.afterInvalidDrop){
            
            this.afterInvalidDrop(e, id);
        }
    },

    
    afterRepair : function(){
        if(Ext.enableFx){
            this.el.highlight(this.hlColor || "c3daf9");
        }
        this.dragging = false;
    },

    
    beforeInvalidDrop : function(target, e, id){
        return true;
    },

    
    handleMouseDown : function(e){
        if(this.dragging) {
            return;
        }
        var data = this.getDragData(e);
        if(data && this.onBeforeDrag(data, e) !== false){
            this.dragData = data;
            this.proxy.stop();
            Ext.dd.DragSource.superclass.handleMouseDown.apply(this, arguments);
        } 
    },

    
    onBeforeDrag : function(data, e){
        return true;
    },

    
    onStartDrag : Ext.emptyFn,

    
    startDrag : function(x, y){
        this.proxy.reset();
        this.dragging = true;
        this.proxy.update("");
        this.onInitDrag(x, y);
        this.proxy.show();
    },

    
    onInitDrag : function(x, y){
        var clone = this.el.dom.cloneNode(true);
        clone.id = Ext.id(); 
        this.proxy.update(clone);
        this.onStartDrag(x, y);
        return true;
    },

    
    getProxy : function(){
        return this.proxy;  
    },

    
    hideProxy : function(){
        this.proxy.hide();  
        this.proxy.reset(true);
        this.dragging = false;
    },

    
    triggerCacheRefresh : function(){
        Ext.dd.DDM.refreshCache(this.groups);
    },

    
    b4EndDrag: function(e) {
    },

    
    endDrag : function(e){
        this.onEndDrag(this.dragData, e);
    },

    
    onEndDrag : function(data, e){
    },
    
    
    autoOffset : function(x, y) {
        this.setDelta(-12, -20);
    },
    
    destroy: function(){
        Ext.dd.DragSource.superclass.destroy.call(this);
        Ext.destroy(this.proxy);
    }
});
Ext.dd.DropTarget = function(el, config){
    this.el = Ext.get(el);
    
    Ext.apply(this, config);
    
    if(this.containerScroll){
        Ext.dd.ScrollManager.register(this.el);
    }
    
    Ext.dd.DropTarget.superclass.constructor.call(this, this.el.dom, this.ddGroup || this.group, 
          {isTarget: true});

};

Ext.extend(Ext.dd.DropTarget, Ext.dd.DDTarget, {
    
    
    
    dropAllowed : "x-dd-drop-ok",
    
    dropNotAllowed : "x-dd-drop-nodrop",

    
    isTarget : true,

    
    isNotifyTarget : true,

    
    notifyEnter : function(dd, e, data){
        if(this.overClass){
            this.el.addClass(this.overClass);
        }
        return this.dropAllowed;
    },

    
    notifyOver : function(dd, e, data){
        return this.dropAllowed;
    },

    
    notifyOut : function(dd, e, data){
        if(this.overClass){
            this.el.removeClass(this.overClass);
        }
    },

    
    notifyDrop : function(dd, e, data){
        return false;
    }
});
Ext.dd.DragZone = function(el, config){
    Ext.dd.DragZone.superclass.constructor.call(this, el, config);
    if(this.containerScroll){
        Ext.dd.ScrollManager.register(this.el);
    }
};

Ext.extend(Ext.dd.DragZone, Ext.dd.DragSource, {
    
    
    

    
    getDragData : function(e){
        return Ext.dd.Registry.getHandleFromEvent(e);
    },
    
    
    onInitDrag : function(x, y){
        this.proxy.update(this.dragData.ddel.cloneNode(true));
        this.onStartDrag(x, y);
        return true;
    },
    
    
    afterRepair : function(){
        if(Ext.enableFx){
            Ext.Element.fly(this.dragData.ddel).highlight(this.hlColor || "c3daf9");
        }
        this.dragging = false;
    },

    
    getRepairXY : function(e){
        return Ext.Element.fly(this.dragData.ddel).getXY();  
    }
});
Ext.dd.DropZone = function(el, config){
    Ext.dd.DropZone.superclass.constructor.call(this, el, config);
};

Ext.extend(Ext.dd.DropZone, Ext.dd.DropTarget, {
    
    getTargetFromEvent : function(e){
        return Ext.dd.Registry.getTargetFromEvent(e);
    },

    
    onNodeEnter : function(n, dd, e, data){
        
    },

    
    onNodeOver : function(n, dd, e, data){
        return this.dropAllowed;
    },

    
    onNodeOut : function(n, dd, e, data){
        
    },

    
    onNodeDrop : function(n, dd, e, data){
        return false;
    },

    
    onContainerOver : function(dd, e, data){
        return this.dropNotAllowed;
    },

    
    onContainerDrop : function(dd, e, data){
        return false;
    },

    
    notifyEnter : function(dd, e, data){
        return this.dropNotAllowed;
    },

    
    notifyOver : function(dd, e, data){
        var n = this.getTargetFromEvent(e);
        if(!n){ 
            if(this.lastOverNode){
                this.onNodeOut(this.lastOverNode, dd, e, data);
                this.lastOverNode = null;
            }
            return this.onContainerOver(dd, e, data);
        }
        if(this.lastOverNode != n){
            if(this.lastOverNode){
                this.onNodeOut(this.lastOverNode, dd, e, data);
            }
            this.onNodeEnter(n, dd, e, data);
            this.lastOverNode = n;
        }
        return this.onNodeOver(n, dd, e, data);
    },

    
    notifyOut : function(dd, e, data){
        if(this.lastOverNode){
            this.onNodeOut(this.lastOverNode, dd, e, data);
            this.lastOverNode = null;
        }
    },

    
    notifyDrop : function(dd, e, data){
        if(this.lastOverNode){
            this.onNodeOut(this.lastOverNode, dd, e, data);
            this.lastOverNode = null;
        }
        var n = this.getTargetFromEvent(e);
        return n ?
            this.onNodeDrop(n, dd, e, data) :
            this.onContainerDrop(dd, e, data);
    },

    
    triggerCacheRefresh : function(){
        Ext.dd.DDM.refreshCache(this.groups);
    }  
});
Ext.Element.addMethods({
    
    initDD : function(group, config, overrides){
        var dd = new Ext.dd.DD(Ext.id(this.dom), group, config);
        return Ext.apply(dd, overrides);
    },

    
    initDDProxy : function(group, config, overrides){
        var dd = new Ext.dd.DDProxy(Ext.id(this.dom), group, config);
        return Ext.apply(dd, overrides);
    },

    
    initDDTarget : function(group, config, overrides){
        var dd = new Ext.dd.DDTarget(Ext.id(this.dom), group, config);
        return Ext.apply(dd, overrides);
    }
});

Ext.data.Api = (function() {

    
    
    
    
    var validActions = {};

    return {
        
        actions : {
            create  : 'create',
            read    : 'read',
            update  : 'update',
            destroy : 'destroy'
        },

        
        restActions : {
            create  : 'POST',
            read    : 'GET',
            update  : 'PUT',
            destroy : 'DELETE'
        },

        
        isAction : function(action) {
            return (Ext.data.Api.actions[action]) ? true : false;
        },

        
        getVerb : function(name) {
            if (validActions[name]) {
                return validActions[name];  
            }
            for (var verb in this.actions) {
                if (this.actions[verb] === name) {
                    validActions[name] = verb;
                    break;
                }
            }
            return (validActions[name] !== undefined) ? validActions[name] : null;
        },

        
        isValid : function(api){
            var invalid = [];
            var crud = this.actions; 
            for (var action in api) {
                if (!(action in crud)) {
                    invalid.push(action);
                }
            }
            return (!invalid.length) ? true : invalid;
        },

        
        hasUniqueUrl : function(proxy, verb) {
            var url = (proxy.api[verb]) ? proxy.api[verb].url : null;
            var unique = true;
            for (var action in proxy.api) {
                if ((unique = (action === verb) ? true : (proxy.api[action].url != url) ? true : false) === false) {
                    break;
                }
            }
            return unique;
        },

        
        prepare : function(proxy) {
            if (!proxy.api) {
                proxy.api = {}; 
            }
            for (var verb in this.actions) {
                var action = this.actions[verb];
                proxy.api[action] = proxy.api[action] || proxy.url || proxy.directFn;
                if (typeof(proxy.api[action]) == 'string') {
                    proxy.api[action] = {
                        url: proxy.api[action],
                        method: (proxy.restful === true) ? Ext.data.Api.restActions[action] : undefined
                    };
                }
            }
        },

        
        restify : function(proxy) {
            proxy.restful = true;
            for (var verb in this.restActions) {
                proxy.api[this.actions[verb]].method ||
                    (proxy.api[this.actions[verb]].method = this.restActions[verb]);
            }
            
            
            proxy.onWrite = proxy.onWrite.createInterceptor(function(action, o, response, rs) {
                var reader = o.reader;
                var res = new Ext.data.Response({
                    action: action,
                    raw: response
                });

                switch (response.status) {
                    case 200:   
                        return true;
                        break;
                    case 201:   
                        if (Ext.isEmpty(res.raw.responseText)) {
                          res.success = true;
                        } else {
                          
                          return true;
                        }
                        break;
                    case 204:  
                        res.success = true;
                        res.data = null;
                        break;
                    default:
                        return true;
                        break;
                }
                if (res.success === true) {
                    this.fireEvent("write", this, action, res.data, res, rs, o.request.arg);
                } else {
                    this.fireEvent('exception', this, 'remote', action, o, res, rs);
                }
                o.request.callback.call(o.request.scope, res.data, res, res.success);

                return false;   
            }, proxy);
        }
    };
})();


Ext.data.Response = function(params, response) {
    Ext.apply(this, params, {
        raw: response
    });
};
Ext.data.Response.prototype = {
    message : null,
    success : false,
    status : null,
    root : null,
    raw : null,

    getMessage : function() {
        return this.message;
    },
    getSuccess : function() {
        return this.success;
    },
    getStatus : function() {
        return this.status;
    },
    getRoot : function() {
        return this.root;
    },
    getRawResponse : function() {
        return this.raw;
    }
};


Ext.data.Api.Error = Ext.extend(Ext.Error, {
    constructor : function(message, arg) {
        this.arg = arg;
        Ext.Error.call(this, message);
    },
    name: 'Ext.data.Api'
});
Ext.apply(Ext.data.Api.Error.prototype, {
    lang: {
        'action-url-undefined': 'No fallback url defined for this action.  When defining a DataProxy api, please be sure to define an url for each CRUD action in Ext.data.Api.actions or define a default url in addition to your api-configuration.',
        'invalid': 'received an invalid API-configuration.  Please ensure your proxy API-configuration contains only the actions defined in Ext.data.Api.actions',
        'invalid-url': 'Invalid url.  Please review your proxy configuration.',
        'execute': 'Attempted to execute an unknown action.  Valid API actions are defined in Ext.data.Api.actions"'
    }
});




Ext.data.SortTypes = {
    
    none : function(s){
        return s;
    },
    
    
    stripTagsRE : /<\/?[^>]+>/gi,
    
    
    asText : function(s){
        return String(s).replace(this.stripTagsRE, "");
    },
    
    
    asUCText : function(s){
        return String(s).toUpperCase().replace(this.stripTagsRE, "");
    },
    
    
    asUCString : function(s) {
    	return String(s).toUpperCase();
    },
    
    
    asDate : function(s) {
        if(!s){
            return 0;
        }
        if(Ext.isDate(s)){
            return s.getTime();
        }
    	return Date.parse(String(s));
    },
    
    
    asFloat : function(s) {
    	var val = parseFloat(String(s).replace(/,/g, ""));
    	return isNaN(val) ? 0 : val;
    },
    
    
    asInt : function(s) {
        var val = parseInt(String(s).replace(/,/g, ""), 10);
        return isNaN(val) ? 0 : val;
    }
};
Ext.data.Record = function(data, id){
    
    this.id = (id || id === 0) ? id : Ext.data.Record.id(this);
    this.data = data || {};
};


Ext.data.Record.create = function(o){
    var f = Ext.extend(Ext.data.Record, {});
    var p = f.prototype;
    p.fields = new Ext.util.MixedCollection(false, function(field){
        return field.name;
    });
    for(var i = 0, len = o.length; i < len; i++){
        p.fields.add(new Ext.data.Field(o[i]));
    }
    f.getField = function(name){
        return p.fields.get(name);
    };
    return f;
};

Ext.data.Record.PREFIX = 'ext-record';
Ext.data.Record.AUTO_ID = 1;
Ext.data.Record.EDIT = 'edit';
Ext.data.Record.REJECT = 'reject';
Ext.data.Record.COMMIT = 'commit';



Ext.data.Record.id = function(rec) {
    rec.phantom = true;
    return [Ext.data.Record.PREFIX, '-', Ext.data.Record.AUTO_ID++].join('');
};

Ext.data.Record.prototype = {
    
    
    
    
    
    
    dirty : false,
    editing : false,
    error : null,
    
    modified : null,
    
    phantom : false,

    
    join : function(store){
        
        this.store = store;
    },

    
    set : function(name, value){
        var encode = Ext.isPrimitive(value) ? String : Ext.encode;
        if(encode(this.data[name]) == encode(value)) {
            return;
        }        
        this.dirty = true;
        if(!this.modified){
            this.modified = {};
        }
        if(this.modified[name] === undefined){
            this.modified[name] = this.data[name];
        }
        this.data[name] = value;
        if(!this.editing){
            this.afterEdit();
        }
    },

    
    afterEdit : function(){
        if (this.store != undefined && typeof this.store.afterEdit == "function") {
            this.store.afterEdit(this);
        }
    },

    
    afterReject : function(){
        if(this.store){
            this.store.afterReject(this);
        }
    },

    
    afterCommit : function(){
        if(this.store){
            this.store.afterCommit(this);
        }
    },

    
    get : function(name){
        return this.data[name];
    },

    
    beginEdit : function(){
        this.editing = true;
        this.modified = this.modified || {};
    },

    
    cancelEdit : function(){
        this.editing = false;
        delete this.modified;
    },

    
    endEdit : function(){
        this.editing = false;
        if(this.dirty){
            this.afterEdit();
        }
    },

    
    reject : function(silent){
        var m = this.modified;
        for(var n in m){
            if(typeof m[n] != "function"){
                this.data[n] = m[n];
            }
        }
        this.dirty = false;
        delete this.modified;
        this.editing = false;
        if(silent !== true){
            this.afterReject();
        }
    },

    
    commit : function(silent){
        this.dirty = false;
        delete this.modified;
        this.editing = false;
        if(silent !== true){
            this.afterCommit();
        }
    },

    
    getChanges : function(){
        var m = this.modified, cs = {};
        for(var n in m){
            if(m.hasOwnProperty(n)){
                cs[n] = this.data[n];
            }
        }
        return cs;
    },

    
    hasError : function(){
        return this.error !== null;
    },

    
    clearError : function(){
        this.error = null;
    },

    
    copy : function(newId) {
        return new this.constructor(Ext.apply({}, this.data), newId || this.id);
    },

    
    isModified : function(fieldName){
        return !!(this.modified && this.modified.hasOwnProperty(fieldName));
    },

    
    isValid : function() {
        return this.fields.find(function(f) {
            return (f.allowBlank === false && Ext.isEmpty(this.data[f.name])) ? true : false;
        },this) ? false : true;
    },

    
    markDirty : function(){
        this.dirty = true;
        if(!this.modified){
            this.modified = {};
        }
        this.fields.each(function(f) {
            this.modified[f.name] = this.data[f.name];
        },this);
    }
};

Ext.StoreMgr = Ext.apply(new Ext.util.MixedCollection(), {
    

    
    register : function(){
        for(var i = 0, s; (s = arguments[i]); i++){
            this.add(s);
        }
    },

    
    unregister : function(){
        for(var i = 0, s; (s = arguments[i]); i++){
            this.remove(this.lookup(s));
        }
    },

    
    lookup : function(id){
        if(Ext.isArray(id)){
            var fields = ['field1'], expand = !Ext.isArray(id[0]);
            if(!expand){
                for(var i = 2, len = id[0].length; i <= len; ++i){
                    fields.push('field' + i);
                }
            }
            return new Ext.data.ArrayStore({
                fields: fields,
                data: id,
                expandData: expand,
                autoDestroy: true,
                autoCreated: true

            });
        }
        return Ext.isObject(id) ? (id.events ? id : Ext.create(id, 'store')) : this.get(id);
    },

    
    getKey : function(o){
         return o.storeId;
    }
});
Ext.data.Store = Ext.extend(Ext.util.Observable, {
    
    
    
    
    
    
    
    writer : undefined,
    
    
    
    remoteSort : false,

    
    autoDestroy : false,

    
    pruneModifiedRecords : false,

    
    lastOptions : null,

    
    autoSave : true,

    
    batch : true,

    
    restful: false,

    
    paramNames : undefined,

    
    defaultParamNames : {
        start : 'start',
        limit : 'limit',
        sort : 'sort',
        dir : 'dir'
    },

    
    isDestroyed: false,

    
    hasMultiSort: false,

    
    batchKey : '_ext_batch_',

    constructor : function(config){
        this.data = new Ext.util.MixedCollection(false);
        this.data.getKey = function(o){
            return o.id;
        };


        
        this.removed = [];

        if(config && config.data){
            this.inlineData = config.data;
            delete config.data;
        }

        Ext.apply(this, config);

        
        this.baseParams = Ext.isObject(this.baseParams) ? this.baseParams : {};

        this.paramNames = Ext.applyIf(this.paramNames || {}, this.defaultParamNames);

        if((this.url || this.api) && !this.proxy){
            this.proxy = new Ext.data.HttpProxy({url: this.url, api: this.api});
        }
        
        if (this.restful === true && this.proxy) {
            
            
            this.batch = false;
            Ext.data.Api.restify(this.proxy);
        }

        if(this.reader){ 
            if(!this.recordType){
                this.recordType = this.reader.recordType;
            }
            if(this.reader.onMetaChange){
                this.reader.onMetaChange = this.reader.onMetaChange.createSequence(this.onMetaChange, this);
            }
            if (this.writer) { 
                if (this.writer instanceof(Ext.data.DataWriter) === false) {    
                    this.writer = this.buildWriter(this.writer);
                }
                this.writer.meta = this.reader.meta;
                this.pruneModifiedRecords = true;
            }
        }

        

        if(this.recordType){
            
            this.fields = this.recordType.prototype.fields;
        }
        this.modified = [];

        this.addEvents(
            
            'datachanged',
            
            'metachange',
            
            'add',
            
            'remove',
            
            'update',
            
            'clear',
            
            'exception',
            
            'beforeload',
            
            'load',
            
            'loadexception',
            
            'beforewrite',
            
            'write',
            
            'beforesave',
            
            'save'

        );

        if(this.proxy){
            
            this.relayEvents(this.proxy,  ['loadexception', 'exception']);
        }
        
        if (this.writer) {
            this.on({
                scope: this,
                add: this.createRecords,
                remove: this.destroyRecord,
                update: this.updateRecord,
                clear: this.onClear
            });
        }

        this.sortToggle = {};
        if(this.sortField){
            this.setDefaultSort(this.sortField, this.sortDir);
        }else if(this.sortInfo){
            this.setDefaultSort(this.sortInfo.field, this.sortInfo.direction);
        }

        Ext.data.Store.superclass.constructor.call(this);

        if(this.id){
            this.storeId = this.id;
            delete this.id;
        }
        if(this.storeId){
            Ext.StoreMgr.register(this);
        }
        if(this.inlineData){
            this.loadData(this.inlineData);
            delete this.inlineData;
        }else if(this.autoLoad){
            this.load.defer(10, this, [
                typeof this.autoLoad == 'object' ?
                    this.autoLoad : undefined]);
        }
        
        this.batchCounter = 0;
        this.batches = {};
    },

    
    buildWriter : function(config) {
        var klass = undefined,
            type = (config.format || 'json').toLowerCase();
        switch (type) {
            case 'json':
                klass = Ext.data.JsonWriter;
                break;
            case 'xml':
                klass = Ext.data.XmlWriter;
                break;
            default:
                klass = Ext.data.JsonWriter;
        }
        return new klass(config);
    },

    
    destroy : function(){
        if(!this.isDestroyed){
            if(this.storeId){
                Ext.StoreMgr.unregister(this);
            }
            this.clearData();
            this.data = null;
            Ext.destroy(this.proxy);
            this.reader = this.writer = null;
            this.purgeListeners();
            this.isDestroyed = true;
        }
    },

    
    add : function(records){
        records = [].concat(records);
        if(records.length < 1){
            return;
        }
        for(var i = 0, len = records.length; i < len; i++){
            records[i].join(this);
        }
        var index = this.data.length;
        this.data.addAll(records);
        if(this.snapshot){
            this.snapshot.addAll(records);
        }
        this.fireEvent('add', this, records, index);
    },

    
    addSorted : function(record){
        var index = this.findInsertIndex(record);
        this.insert(index, record);
    },

    
    remove : function(record){
        if(Ext.isArray(record)){
            Ext.each(record, function(r){
                this.remove(r);
            }, this);
            return;
        }
        var index = this.data.indexOf(record);
        if(index > -1){
            record.join(null);
            this.data.removeAt(index);
        }
        if(this.pruneModifiedRecords){
            this.modified.remove(record);
        }
        if(this.snapshot){
            this.snapshot.remove(record);
        }
        if(index > -1){
            this.fireEvent('remove', this, record, index);
        }
    },

    
    removeAt : function(index){
        this.remove(this.getAt(index));
    },

    
    removeAll : function(silent){
        var items = [];
        this.each(function(rec){
            items.push(rec);
        });
        this.clearData();
        if(this.snapshot){
            this.snapshot.clear();
        }
        if(this.pruneModifiedRecords){
            this.modified = [];
        }
        if (silent !== true) {  
            this.fireEvent('clear', this, items);
        }
    },

    
    onClear: function(store, records){
        Ext.each(records, function(rec, index){
            this.destroyRecord(this, rec, index);
        }, this);
    },

    
    insert : function(index, records){
        records = [].concat(records);
        for(var i = 0, len = records.length; i < len; i++){
            this.data.insert(index, records[i]);
            records[i].join(this);
        }
        if(this.snapshot){
            this.snapshot.addAll(records);
        }
        this.fireEvent('add', this, records, index);
    },

    
    indexOf : function(record){
        return this.data.indexOf(record);
    },

    
    indexOfId : function(id){
        return this.data.indexOfKey(id);
    },

    
    getById : function(id){
        return (this.snapshot || this.data).key(id);
    },

    
    getAt : function(index){
        return this.data.itemAt(index);
    },

    
    getRange : function(start, end){
        return this.data.getRange(start, end);
    },

    
    storeOptions : function(o){
        o = Ext.apply({}, o);
        delete o.callback;
        delete o.scope;
        this.lastOptions = o;
    },

    
    clearData: function(){
        this.data.each(function(rec) {
            rec.join(null);
        });
        this.data.clear();
    },

    
    load : function(options) {
        options = Ext.apply({}, options);
        this.storeOptions(options);
        if(this.sortInfo && this.remoteSort){
            var pn = this.paramNames;
            options.params = Ext.apply({}, options.params);
            options.params[pn.sort] = this.sortInfo.field;
            options.params[pn.dir] = this.sortInfo.direction;
        }
        try {
            return this.execute('read', null, options); 
        } catch(e) {
            this.handleException(e);
            return false;
        }
    },

    
    updateRecord : function(store, record, action) {
        if (action == Ext.data.Record.EDIT && this.autoSave === true && (!record.phantom || (record.phantom && record.isValid()))) {
            this.save();
        }
    },

    
    createRecords : function(store, rs, index) {
        for (var i = 0, len = rs.length; i < len; i++) {
            if (rs[i].phantom && rs[i].isValid()) {
                rs[i].markDirty();  
                this.modified.push(rs[i]);  
            }
        }
        if (this.autoSave === true) {
            this.save();
        }
    },

    
    destroyRecord : function(store, record, index) {
        if (this.modified.indexOf(record) != -1) {  
            this.modified.remove(record);
        }
        if (!record.phantom) {
            this.removed.push(record);

            
            
            
            record.lastIndex = index;

            if (this.autoSave === true) {
                this.save();
            }
        }
    },

    
    execute : function(action, rs, options,  batch) {
        
        if (!Ext.data.Api.isAction(action)) {
            throw new Ext.data.Api.Error('execute', action);
        }
        
        options = Ext.applyIf(options||{}, {
            params: {}
        });
        if(batch !== undefined){
            this.addToBatch(batch);
        }
        
        
        var doRequest = true;

        if (action === 'read') {
            doRequest = this.fireEvent('beforeload', this, options);
            Ext.applyIf(options.params, this.baseParams);
        }
        else {
            
            
            if (this.writer.listful === true && this.restful !== true) {
                rs = (Ext.isArray(rs)) ? rs : [rs];
            }
            
            else if (Ext.isArray(rs) && rs.length == 1) {
                rs = rs.shift();
            }
            
            if ((doRequest = this.fireEvent('beforewrite', this, action, rs, options)) !== false) {
                this.writer.apply(options.params, this.baseParams, action, rs);
            }
        }
        if (doRequest !== false) {
            
            if (this.writer && this.proxy.url && !this.proxy.restful && !Ext.data.Api.hasUniqueUrl(this.proxy, action)) {
                options.params.xaction = action;    
            }
            
            
            
            
            
            this.proxy.request(Ext.data.Api.actions[action], rs, options.params, this.reader, this.createCallback(action, rs, batch), this, options);
        }
        return doRequest;
    },

    
    save : function() {
        if (!this.writer) {
            throw new Ext.data.Store.Error('writer-undefined');
        }

        var queue = [],
            len,
            trans,
            batch,
            data = {};
        
        if(this.removed.length){
            queue.push(['destroy', this.removed]);
        }

        
        var rs = [].concat(this.getModifiedRecords());
        if(rs.length){
            
            var phantoms = [];
            for(var i = rs.length-1; i >= 0; i--){
                if(rs[i].phantom === true){
                    var rec = rs.splice(i, 1).shift();
                    if(rec.isValid()){
                        phantoms.push(rec);
                    }
                }else if(!rs[i].isValid()){ 
                    rs.splice(i,1);
                }
            }
            
            if(phantoms.length){
                queue.push(['create', phantoms]);
            }

            
            if(rs.length){
                queue.push(['update', rs]);
            }
        }
        len = queue.length;
        if(len){
            batch = ++this.batchCounter;
            for(var i = 0; i < len; ++i){
                trans = queue[i];
                data[trans[0]] = trans[1];
            }
            if(this.fireEvent('beforesave', this, data) !== false){
                for(var i = 0; i < len; ++i){
                    trans = queue[i];
                    this.doTransaction(trans[0], trans[1], batch);
                }
                return batch;
            }
        }
        return -1;
    },

    
    doTransaction : function(action, rs, batch) {
        function transaction(records) {
            try{
                this.execute(action, records, undefined, batch);
            }catch (e){
                this.handleException(e);
            }
        }
        if(this.batch === false){
            for(var i = 0, len = rs.length; i < len; i++){
                transaction.call(this, rs[i]);
            }
        }else{
            transaction.call(this, rs);
        }
    },

    
    addToBatch : function(batch){
        var b = this.batches,
            key = this.batchKey + batch,
            o = b[key];

        if(!o){
            b[key] = o = {
                id: batch,
                count: 0,
                data: {}
            };
        }
        ++o.count;
    },

    removeFromBatch : function(batch, action, data){
        var b = this.batches,
            key = this.batchKey + batch,
            o = b[key],
            data,
            arr;


        if(o){
            arr = o.data[action] || [];
            o.data[action] = arr.concat(data);
            if(o.count === 1){
                data = o.data;
                delete b[key];
                this.fireEvent('save', this, batch, data);
            }else{
                --o.count;
            }
        }
    },

    
    
    createCallback : function(action, rs, batch) {
        var actions = Ext.data.Api.actions;
        return (action == 'read') ? this.loadRecords : function(data, response, success) {
            
            this['on' + Ext.util.Format.capitalize(action) + 'Records'](success, rs, [].concat(data));
            
            if (success === true) {
                this.fireEvent('write', this, action, data, response, rs);
            }
            this.removeFromBatch(batch, action, data);
        };
    },

    
    
    
    clearModified : function(rs) {
        if (Ext.isArray(rs)) {
            for (var n=rs.length-1;n>=0;n--) {
                this.modified.splice(this.modified.indexOf(rs[n]), 1);
            }
        } else {
            this.modified.splice(this.modified.indexOf(rs), 1);
        }
    },

    
    reMap : function(record) {
        if (Ext.isArray(record)) {
            for (var i = 0, len = record.length; i < len; i++) {
                this.reMap(record[i]);
            }
        } else {
            delete this.data.map[record._phid];
            this.data.map[record.id] = record;
            var index = this.data.keys.indexOf(record._phid);
            this.data.keys.splice(index, 1, record.id);
            delete record._phid;
        }
    },

    
    onCreateRecords : function(success, rs, data) {
        if (success === true) {
            try {
                this.reader.realize(rs, data);
                this.reMap(rs);
            }
            catch (e) {
                this.handleException(e);
                if (Ext.isArray(rs)) {
                    
                    this.onCreateRecords(success, rs, data);
                }
            }
        }
    },

    
    onUpdateRecords : function(success, rs, data) {
        if (success === true) {
            try {
                this.reader.update(rs, data);
            } catch (e) {
                this.handleException(e);
                if (Ext.isArray(rs)) {
                    
                    this.onUpdateRecords(success, rs, data);
                }
            }
        }
    },

    
    onDestroyRecords : function(success, rs, data) {
        
        rs = (rs instanceof Ext.data.Record) ? [rs] : [].concat(rs);
        for (var i=0,len=rs.length;i<len;i++) {
            this.removed.splice(this.removed.indexOf(rs[i]), 1);
        }
        if (success === false) {
            
            
            for (i=rs.length-1;i>=0;i--) {
                this.insert(rs[i].lastIndex, rs[i]);    
            }
        }
    },

    
    handleException : function(e) {
        
        Ext.handleError(e);
    },

    
    reload : function(options){
        this.load(Ext.applyIf(options||{}, this.lastOptions));
    },

    
    
    loadRecords : function(o, options, success){
        if (this.isDestroyed === true) {
            return;
        }
        if(!o || success === false){
            if(success !== false){
                this.fireEvent('load', this, [], options);
            }
            if(options.callback){
                options.callback.call(options.scope || this, [], options, false, o);
            }
            return;
        }
        var r = o.records, t = o.totalRecords || r.length;
        if(!options || options.add !== true){
            if(this.pruneModifiedRecords){
                this.modified = [];
            }
            for(var i = 0, len = r.length; i < len; i++){
                r[i].join(this);
            }
            if(this.snapshot){
                this.data = this.snapshot;
                delete this.snapshot;
            }
            this.clearData();
            this.data.addAll(r);
            this.totalLength = t;
            this.applySort();
            this.fireEvent('datachanged', this);
        }else{
            this.totalLength = Math.max(t, this.data.length+r.length);
            this.add(r);
        }
        this.fireEvent('load', this, r, options);
        if(options.callback){
            options.callback.call(options.scope || this, r, options, true);
        }
    },

    
    loadData : function(o, append){
        var r = this.reader.readRecords(o);
        this.loadRecords(r, {add: append}, true);
    },

    
    getCount : function(){
        return this.data.length || 0;
    },

    
    getTotalCount : function(){
        return this.totalLength || 0;
    },

    
    getSortState : function(){
        return this.sortInfo;
    },

    
    applySort : function(){
        if ((this.sortInfo || this.multiSortInfo) && !this.remoteSort) {
            this.sortData();
        }
    },

    
    sortData : function() {
        var sortInfo  = this.hasMultiSort ? this.multiSortInfo : this.sortInfo,
            direction = sortInfo.direction || "ASC",
            sorters   = sortInfo.sorters,
            sortFns   = [];

        
        if (!this.hasMultiSort) {
            sorters = [{direction: direction, field: sortInfo.field}];
        }

        
        for (var i=0, j = sorters.length; i < j; i++) {
            sortFns.push(this.createSortFunction(sorters[i].field, sorters[i].direction));
        }
        
        if (sortFns.length == 0) {
            return;
        }

        
        
        var directionModifier = direction.toUpperCase() == "DESC" ? -1 : 1;

        
        var fn = function(r1, r2) {
          var result = sortFns[0].call(this, r1, r2);

          
          if (sortFns.length > 1) {
              for (var i=1, j = sortFns.length; i < j; i++) {
                  result = result || sortFns[i].call(this, r1, r2);
              }
          }

          return directionModifier * result;
        };

        
        this.data.sort(direction, fn);
        if (this.snapshot && this.snapshot != this.data) {
            this.snapshot.sort(direction, fn);
        }
    },

    
    createSortFunction: function(field, direction) {
        direction = direction || "ASC";
        var directionModifier = direction.toUpperCase() == "DESC" ? -1 : 1;

        var sortType = this.fields.get(field).sortType;

        
        
        return function(r1, r2) {
            var v1 = sortType(r1.data[field]),
                v2 = sortType(r2.data[field]);

            return directionModifier * (v1 > v2 ? 1 : (v1 < v2 ? -1 : 0));
        };
    },

    
    setDefaultSort : function(field, dir) {
        dir = dir ? dir.toUpperCase() : 'ASC';
        this.sortInfo = {field: field, direction: dir};
        this.sortToggle[field] = dir;
    },

    
    sort : function(fieldName, dir) {
        if (Ext.isArray(arguments[0])) {
            return this.multiSort.call(this, fieldName, dir);
        } else {
            return this.singleSort(fieldName, dir);
        }
    },

    
    singleSort: function(fieldName, dir) {
        var field = this.fields.get(fieldName);
        if (!field) return false;

        var name       = field.name,
            sortInfo   = this.sortInfo || null,
            sortToggle = this.sortToggle ? this.sortToggle[name] : null;

        if (!dir) {
            if (sortInfo && sortInfo.field == name) { 
                dir = (this.sortToggle[name] || 'ASC').toggle('ASC', 'DESC');
            } else {
                dir = field.sortDir;
            }
        }

        this.sortToggle[name] = dir;
        this.sortInfo = {field: name, direction: dir};
        this.hasMultiSort = false;

        if (this.remoteSort) {
            if (!this.load(this.lastOptions)) {
                if (sortToggle) {
                    this.sortToggle[name] = sortToggle;
                }
                if (sortInfo) {
                    this.sortInfo = sortInfo;
                }
            }
        } else {
            this.applySort();
            this.fireEvent('datachanged', this);
        }
    },

    
    multiSort: function(sorters, direction) {
        this.hasMultiSort = true;
        direction = direction || "ASC";

        
        if (this.multiSortInfo && direction == this.multiSortInfo.direction) {
            direction = direction.toggle("ASC", "DESC");
        }

        
        this.multiSortInfo = {
            sorters  : sorters,
            direction: direction
        };
        
        if (this.remoteSort) {
            this.singleSort(sorters[0].field, sorters[0].direction);

        } else {
            this.applySort();
            this.fireEvent('datachanged', this);
        }
    },

    
    each : function(fn, scope){
        this.data.each(fn, scope);
    },

    
    getModifiedRecords : function(){
        return this.modified;
    },

    
    sum : function(property, start, end){
        var rs = this.data.items, v = 0;
        start = start || 0;
        end = (end || end === 0) ? end : rs.length-1;

        for(var i = start; i <= end; i++){
            v += (rs[i].data[property] || 0);
        }
        return v;
    },

    
    createFilterFn : function(property, value, anyMatch, caseSensitive, exactMatch){
        if(Ext.isEmpty(value, false)){
            return false;
        }
        value = this.data.createValueMatcher(value, anyMatch, caseSensitive, exactMatch);
        return function(r) {
            return value.test(r.data[property]);
        };
    },

    
    createMultipleFilterFn: function(filters) {
        return function(record) {
            var isMatch = true;

            for (var i=0, j = filters.length; i < j; i++) {
                var filter = filters[i],
                    fn     = filter.fn,
                    scope  = filter.scope;

                isMatch = isMatch && fn.call(scope, record);
            }

            return isMatch;
        };
    },

    
    filter : function(property, value, anyMatch, caseSensitive, exactMatch){
        
        if (Ext.isObject(property)) {
            property = [property];
        }

        if (Ext.isArray(property)) {
            var filters = [];

            
            for (var i=0, j = property.length; i < j; i++) {
                var filter = property[i],
                    func   = filter.fn,
                    scope  = filter.scope || this;

                
                if (!Ext.isFunction(func)) {
                    func = this.createFilterFn(filter.property, filter.value, filter.anyMatch, filter.caseSensitive, filter.exactMatch);
                }

                filters.push({fn: func, scope: scope});
            }

            var fn = this.createMultipleFilterFn(filters);
        } else {
            
            var fn = this.createFilterFn(property, value, anyMatch, caseSensitive, exactMatch);
        }

        return fn ? this.filterBy(fn) : this.clearFilter();
    },

    
    filterBy : function(fn, scope){
        this.snapshot = this.snapshot || this.data;
        this.data = this.queryBy(fn, scope||this);
        this.fireEvent('datachanged', this);
    },

    
    clearFilter : function(suppressEvent){
        if(this.isFiltered()){
            this.data = this.snapshot;
            delete this.snapshot;
            if(suppressEvent !== true){
                this.fireEvent('datachanged', this);
            }
        }
    },

    
    isFiltered : function(){
        return !!this.snapshot && this.snapshot != this.data;
    },

    
    query : function(property, value, anyMatch, caseSensitive){
        var fn = this.createFilterFn(property, value, anyMatch, caseSensitive);
        return fn ? this.queryBy(fn) : this.data.clone();
    },

    
    queryBy : function(fn, scope){
        var data = this.snapshot || this.data;
        return data.filterBy(fn, scope||this);
    },

    
    find : function(property, value, start, anyMatch, caseSensitive){
        var fn = this.createFilterFn(property, value, anyMatch, caseSensitive);
        return fn ? this.data.findIndexBy(fn, null, start) : -1;
    },

    
    findExact: function(property, value, start){
        return this.data.findIndexBy(function(rec){
            return rec.get(property) === value;
        }, this, start);
    },

    
    findBy : function(fn, scope, start){
        return this.data.findIndexBy(fn, scope, start);
    },

    
    collect : function(dataIndex, allowNull, bypassFilter){
        var d = (bypassFilter === true && this.snapshot) ?
                this.snapshot.items : this.data.items;
        var v, sv, r = [], l = {};
        for(var i = 0, len = d.length; i < len; i++){
            v = d[i].data[dataIndex];
            sv = String(v);
            if((allowNull || !Ext.isEmpty(v)) && !l[sv]){
                l[sv] = true;
                r[r.length] = v;
            }
        }
        return r;
    },

    
    afterEdit : function(record){
        if(this.modified.indexOf(record) == -1){
            this.modified.push(record);
        }
        this.fireEvent('update', this, record, Ext.data.Record.EDIT);
    },

    
    afterReject : function(record){
        this.modified.remove(record);
        this.fireEvent('update', this, record, Ext.data.Record.REJECT);
    },

    
    afterCommit : function(record){
        this.modified.remove(record);
        this.fireEvent('update', this, record, Ext.data.Record.COMMIT);
    },

    
    commitChanges : function(){
        var m = this.modified.slice(0);
        this.modified = [];
        for(var i = 0, len = m.length; i < len; i++){
            m[i].commit();
        }
    },

    
    rejectChanges : function(){
        var m = this.modified.slice(0);
        this.modified = [];
        for(var i = 0, len = m.length; i < len; i++){
            m[i].reject();
        }
        var m = this.removed.slice(0).reverse();
        this.removed = [];
        for(var i = 0, len = m.length; i < len; i++){
            this.insert(m[i].lastIndex||0, m[i]);
            m[i].reject();
        }
    },

    
    onMetaChange : function(meta){
        this.recordType = this.reader.recordType;
        this.fields = this.recordType.prototype.fields;
        delete this.snapshot;
        if(this.reader.meta.sortInfo){
            this.sortInfo = this.reader.meta.sortInfo;
        }else if(this.sortInfo  && !this.fields.get(this.sortInfo.field)){
            delete this.sortInfo;
        }
        if(this.writer){
            this.writer.meta = this.reader.meta;
        }
        this.modified = [];
        this.fireEvent('metachange', this, this.reader.meta);
    },

    
    findInsertIndex : function(record){
        this.suspendEvents();
        var data = this.data.clone();
        this.data.add(record);
        this.applySort();
        var index = this.data.indexOf(record);
        this.data = data;
        this.resumeEvents();
        return index;
    },

    
    setBaseParam : function (name, value){
        this.baseParams = this.baseParams || {};
        this.baseParams[name] = value;
    }
});

Ext.reg('store', Ext.data.Store);


Ext.data.Store.Error = Ext.extend(Ext.Error, {
    name: 'Ext.data.Store'
});
Ext.apply(Ext.data.Store.Error.prototype, {
    lang: {
        'writer-undefined' : 'Attempted to execute a write-action without a DataWriter installed.'
    }
});

Ext.data.Field = Ext.extend(Object, {
    
    constructor : function(config){
        if(Ext.isString(config)){
            config = {name: config};
        }
        Ext.apply(this, config);
        
        var types = Ext.data.Types,
            st = this.sortType,
            t;

        if(this.type){
            if(Ext.isString(this.type)){
                this.type = Ext.data.Types[this.type.toUpperCase()] || types.AUTO;
            }
        }else{
            this.type = types.AUTO;
        }

        
        if(Ext.isString(st)){
            this.sortType = Ext.data.SortTypes[st];
        }else if(Ext.isEmpty(st)){
            this.sortType = this.type.sortType;
        }

        if(!this.convert){
            this.convert = this.type.convert;
        }
    },
    
    
    
    
    
    dateFormat: null,
    
    defaultValue: "",
    
    mapping: null,
    
    sortType : null,
    
    sortDir : "ASC",
    
    allowBlank : true
});

Ext.data.DataReader = function(meta, recordType){
    
    this.meta = meta;
    
    this.recordType = Ext.isArray(recordType) ?
        Ext.data.Record.create(recordType) : recordType;

    
    if (this.recordType){
        this.buildExtractors();
    }
};

Ext.data.DataReader.prototype = {
    
    
    getTotal: Ext.emptyFn,
    
    getRoot: Ext.emptyFn,
    
    getMessage: Ext.emptyFn,
    
    getSuccess: Ext.emptyFn,
    
    getId: Ext.emptyFn,
    
    buildExtractors : Ext.emptyFn,
    
    extractValues : Ext.emptyFn,

    
    realize: function(rs, data){
        if (Ext.isArray(rs)) {
            for (var i = rs.length - 1; i >= 0; i--) {
                
                if (Ext.isArray(data)) {
                    this.realize(rs.splice(i,1).shift(), data.splice(i,1).shift());
                }
                else {
                    
                    
                    this.realize(rs.splice(i,1).shift(), data);
                }
            }
        }
        else {
            
            if (Ext.isArray(data) && data.length == 1) {
                data = data.shift();
            }
            if (!this.isData(data)) {
                
                
                throw new Ext.data.DataReader.Error('realize', rs);
            }
            rs.phantom = false; 
            rs._phid = rs.id;  
            rs.id = this.getId(data);
            rs.data = data;

            rs.commit();
        }
    },

    
    update : function(rs, data) {
        if (Ext.isArray(rs)) {
            for (var i=rs.length-1; i >= 0; i--) {
                if (Ext.isArray(data)) {
                    this.update(rs.splice(i,1).shift(), data.splice(i,1).shift());
                }
                else {
                    
                    
                    this.update(rs.splice(i,1).shift(), data);
                }
            }
        }
        else {
            
            if (Ext.isArray(data) && data.length == 1) {
                data = data.shift();
            }
            if (this.isData(data)) {
                rs.data = Ext.apply(rs.data, data);
            }
            rs.commit();
        }
    },

    
    extractData : function(root, returnRecords) {
        
        var rawName = (this instanceof Ext.data.JsonReader) ? 'json' : 'node';

        var rs = [];

        
        
        if (this.isData(root) && !(this instanceof Ext.data.XmlReader)) {
            root = [root];
        }
        var f       = this.recordType.prototype.fields,
            fi      = f.items,
            fl      = f.length,
            rs      = [];
        if (returnRecords === true) {
            var Record = this.recordType;
            for (var i = 0; i < root.length; i++) {
                var n = root[i];
                var record = new Record(this.extractValues(n, fi, fl), this.getId(n));
                record[rawName] = n;    
                rs.push(record);
            }
        }
        else {
            for (var i = 0; i < root.length; i++) {
                var data = this.extractValues(root[i], fi, fl);
                data[this.meta.idProperty] = this.getId(root[i]);
                rs.push(data);
            }
        }
        return rs;
    },

    
    isData : function(data) {
        return (data && Ext.isObject(data) && !Ext.isEmpty(this.getId(data))) ? true : false;
    },

    
    onMetaChange : function(meta){
        delete this.ef;
        this.meta = meta;
        this.recordType = Ext.data.Record.create(meta.fields);
        this.buildExtractors();
    }
};


Ext.data.DataReader.Error = Ext.extend(Ext.Error, {
    constructor : function(message, arg) {
        this.arg = arg;
        Ext.Error.call(this, message);
    },
    name: 'Ext.data.DataReader'
});
Ext.apply(Ext.data.DataReader.Error.prototype, {
    lang : {
        'update': "#update received invalid data from server.  Please see docs for DataReader#update and review your DataReader configuration.",
        'realize': "#realize was called with invalid remote-data.  Please see the docs for DataReader#realize and review your DataReader configuration.",
        'invalid-response': "#readResponse received an invalid response from the server."
    }
});

Ext.data.DataWriter = function(config){
    Ext.apply(this, config);
};
Ext.data.DataWriter.prototype = {

    
    writeAllFields : false,
    
    listful : false,    

    
    apply : function(params, baseParams, action, rs) {
        var data    = [],
        renderer    = action + 'Record';
        
        if (Ext.isArray(rs)) {
            Ext.each(rs, function(rec){
                data.push(this[renderer](rec));
            }, this);
        }
        else if (rs instanceof Ext.data.Record) {
            data = this[renderer](rs);
        }
        this.render(params, baseParams, data);
    },

    
    render : Ext.emptyFn,

    
    updateRecord : Ext.emptyFn,

    
    createRecord : Ext.emptyFn,

    
    destroyRecord : Ext.emptyFn,

    
    toHash : function(rec, config) {
        var map = rec.fields.map,
            data = {},
            raw = (this.writeAllFields === false && rec.phantom === false) ? rec.getChanges() : rec.data,
            m;
        Ext.iterate(raw, function(prop, value){
            if((m = map[prop])){
                data[m.mapping ? m.mapping : m.name] = value;
            }
        });
        
        
        
        if (rec.phantom) {
            if (rec.fields.containsKey(this.meta.idProperty) && Ext.isEmpty(rec.data[this.meta.idProperty])) {
                delete data[this.meta.idProperty];
            }
        } else {
            data[this.meta.idProperty] = rec.id
        }
        return data;
    },

    
    toArray : function(data) {
        var fields = [];
        Ext.iterate(data, function(k, v) {fields.push({name: k, value: v});},this);
        return fields;
    }
};
Ext.data.DataProxy = function(conn){
    
    
    conn = conn || {};

    
    
    

    this.api     = conn.api;
    this.url     = conn.url;
    this.restful = conn.restful;
    this.listeners = conn.listeners;

    
    this.prettyUrls = conn.prettyUrls;

    

    this.addEvents(
        
        'exception',
        
        'beforeload',
        
        'load',
        
        'loadexception',
        
        'beforewrite',
        
        'write'
    );
    Ext.data.DataProxy.superclass.constructor.call(this);

    
    try {
        Ext.data.Api.prepare(this);
    } catch (e) {
        if (e instanceof Ext.data.Api.Error) {
            e.toConsole();
        }
    }
    
    Ext.data.DataProxy.relayEvents(this, ['beforewrite', 'write', 'exception']);
};

Ext.extend(Ext.data.DataProxy, Ext.util.Observable, {
    
    restful: false,

    
    setApi : function() {
        if (arguments.length == 1) {
            var valid = Ext.data.Api.isValid(arguments[0]);
            if (valid === true) {
                this.api = arguments[0];
            }
            else {
                throw new Ext.data.Api.Error('invalid', valid);
            }
        }
        else if (arguments.length == 2) {
            if (!Ext.data.Api.isAction(arguments[0])) {
                throw new Ext.data.Api.Error('invalid', arguments[0]);
            }
            this.api[arguments[0]] = arguments[1];
        }
        Ext.data.Api.prepare(this);
    },

    
    isApiAction : function(action) {
        return (this.api[action]) ? true : false;
    },

    
    request : function(action, rs, params, reader, callback, scope, options) {
        if (!this.api[action] && !this.load) {
            throw new Ext.data.DataProxy.Error('action-undefined', action);
        }
        params = params || {};
        if ((action === Ext.data.Api.actions.read) ? this.fireEvent("beforeload", this, params) : this.fireEvent("beforewrite", this, action, rs, params) !== false) {
            this.doRequest.apply(this, arguments);
        }
        else {
            callback.call(scope || this, null, options, false);
        }
    },


    
    load : null,

    
    doRequest : function(action, rs, params, reader, callback, scope, options) {
        
        
        
        this.load(params, reader, callback, scope, options);
    },

    
    onRead : Ext.emptyFn,
    
    onWrite : Ext.emptyFn,
    
    buildUrl : function(action, record) {
        record = record || null;

        
        
        
        var url = (this.conn && this.conn.url) ? this.conn.url : (this.api[action]) ? this.api[action].url : this.url;
        if (!url) {
            throw new Ext.data.Api.Error('invalid-url', action);
        }

        
        
        
        
        
        
        var provides = null;
        var m = url.match(/(.*)(\.json|\.xml|\.html)$/);
        if (m) {
            provides = m[2];    
            url      = m[1];    
        }
        
        if ((this.restful === true || this.prettyUrls === true) && record instanceof Ext.data.Record && !record.phantom) {
            url += '/' + record.id;
        }
        return (provides === null) ? url : url + provides;
    },

    
    destroy: function(){
        this.purgeListeners();
    }
});



Ext.apply(Ext.data.DataProxy, Ext.util.Observable.prototype);
Ext.util.Observable.call(Ext.data.DataProxy);


Ext.data.DataProxy.Error = Ext.extend(Ext.Error, {
    constructor : function(message, arg) {
        this.arg = arg;
        Ext.Error.call(this, message);
    },
    name: 'Ext.data.DataProxy'
});
Ext.apply(Ext.data.DataProxy.Error.prototype, {
    lang: {
        'action-undefined': "DataProxy attempted to execute an API-action but found an undefined url / function.  Please review your Proxy url/api-configuration.",
        'api-invalid': 'Recieved an invalid API-configuration.  Please ensure your proxy API-configuration contains only the actions from Ext.data.Api.actions.'
    }
});



Ext.data.Request = function(params) {
    Ext.apply(this, params);
};
Ext.data.Request.prototype = {
    
    action : undefined,
    
    rs : undefined,
    
    params: undefined,
    
    callback : Ext.emptyFn,
    
    scope : undefined,
    
    reader : undefined
};

Ext.data.Response = function(params) {
    Ext.apply(this, params);
};
Ext.data.Response.prototype = {
    
    action: undefined,
    
    success : undefined,
    
    message : undefined,
    
    data: undefined,
    
    raw: undefined,
    
    records: undefined
};

Ext.data.ScriptTagProxy = function(config){
    Ext.apply(this, config);

    Ext.data.ScriptTagProxy.superclass.constructor.call(this, config);

    this.head = document.getElementsByTagName("head")[0];

    
};

Ext.data.ScriptTagProxy.TRANS_ID = 1000;

Ext.extend(Ext.data.ScriptTagProxy, Ext.data.DataProxy, {
    
    
    timeout : 30000,
    
    callbackParam : "callback",
    
    nocache : true,

    
    doRequest : function(action, rs, params, reader, callback, scope, arg) {
        var p = Ext.urlEncode(Ext.apply(params, this.extraParams));

        var url = this.buildUrl(action, rs);
        if (!url) {
            throw new Ext.data.Api.Error('invalid-url', url);
        }
        url = Ext.urlAppend(url, p);

        if(this.nocache){
            url = Ext.urlAppend(url, '_dc=' + (new Date().getTime()));
        }
        var transId = ++Ext.data.ScriptTagProxy.TRANS_ID;
        var trans = {
            id : transId,
            action: action,
            cb : "stcCallback"+transId,
            scriptId : "stcScript"+transId,
            params : params,
            arg : arg,
            url : url,
            callback : callback,
            scope : scope,
            reader : reader
        };
        window[trans.cb] = this.createCallback(action, rs, trans);
        url += String.format("&{0}={1}", this.callbackParam, trans.cb);
        if(this.autoAbort !== false){
            this.abort();
        }

        trans.timeoutId = this.handleFailure.defer(this.timeout, this, [trans]);

        var script = document.createElement("script");
        script.setAttribute("src", url);
        script.setAttribute("type", "text/javascript");
        script.setAttribute("id", trans.scriptId);
        this.head.appendChild(script);

        this.trans = trans;
    },

    
    createCallback : function(action, rs, trans) {
        var self = this;
        return function(res) {
            self.trans = false;
            self.destroyTrans(trans, true);
            if (action === Ext.data.Api.actions.read) {
                self.onRead.call(self, action, trans, res);
            } else {
                self.onWrite.call(self, action, trans, res, rs);
            }
        };
    },
    
    onRead : function(action, trans, res) {
        var result;
        try {
            result = trans.reader.readRecords(res);
        }catch(e){
            
            this.fireEvent("loadexception", this, trans, res, e);

            this.fireEvent('exception', this, 'response', action, trans, res, e);
            trans.callback.call(trans.scope||window, null, trans.arg, false);
            return;
        }
        if (result.success === false) {
            
            this.fireEvent('loadexception', this, trans, res);

            this.fireEvent('exception', this, 'remote', action, trans, res, null);
        } else {
            this.fireEvent("load", this, res, trans.arg);
        }
        trans.callback.call(trans.scope||window, result, trans.arg, result.success);
    },
    
    onWrite : function(action, trans, response, rs) {
        var reader = trans.reader;
        try {
            
            var res = reader.readResponse(action, response);
        } catch (e) {
            this.fireEvent('exception', this, 'response', action, trans, res, e);
            trans.callback.call(trans.scope||window, null, res, false);
            return;
        }
        if(!res.success === true){
            this.fireEvent('exception', this, 'remote', action, trans, res, rs);
            trans.callback.call(trans.scope||window, null, res, false);
            return;
        }
        this.fireEvent("write", this, action, res.data, res, rs, trans.arg );
        trans.callback.call(trans.scope||window, res.data, res, true);
    },

    
    isLoading : function(){
        return this.trans ? true : false;
    },

    
    abort : function(){
        if(this.isLoading()){
            this.destroyTrans(this.trans);
        }
    },

    
    destroyTrans : function(trans, isLoaded){
        this.head.removeChild(document.getElementById(trans.scriptId));
        clearTimeout(trans.timeoutId);
        if(isLoaded){
            window[trans.cb] = undefined;
            try{
                delete window[trans.cb];
            }catch(e){}
        }else{
            
            window[trans.cb] = function(){
                window[trans.cb] = undefined;
                try{
                    delete window[trans.cb];
                }catch(e){}
            };
        }
    },

    
    handleFailure : function(trans){
        this.trans = false;
        this.destroyTrans(trans, false);
        if (trans.action === Ext.data.Api.actions.read) {
            
            this.fireEvent("loadexception", this, null, trans.arg);
        }

        this.fireEvent('exception', this, 'response', trans.action, {
            response: null,
            options: trans.arg
        });
        trans.callback.call(trans.scope||window, null, trans.arg, false);
    },

    
    destroy: function(){
        this.abort();
        Ext.data.ScriptTagProxy.superclass.destroy.call(this);
    }
});
Ext.data.HttpProxy = function(conn){
    Ext.data.HttpProxy.superclass.constructor.call(this, conn);

    
    this.conn = conn;

    
    
    
    
    this.conn.url = null;

    this.useAjax = !conn || !conn.events;

    
    var actions = Ext.data.Api.actions;
    this.activeRequest = {};
    for (var verb in actions) {
        this.activeRequest[actions[verb]] = undefined;
    }
};

Ext.extend(Ext.data.HttpProxy, Ext.data.DataProxy, {
    
    getConnection : function() {
        return this.useAjax ? Ext.Ajax : this.conn;
    },

    
    setUrl : function(url, makePermanent) {
        this.conn.url = url;
        if (makePermanent === true) {
            this.url = url;
            this.api = null;
            Ext.data.Api.prepare(this);
        }
    },

    
    doRequest : function(action, rs, params, reader, cb, scope, arg) {
        var  o = {
            method: (this.api[action]) ? this.api[action]['method'] : undefined,
            request: {
                callback : cb,
                scope : scope,
                arg : arg
            },
            reader: reader,
            callback : this.createCallback(action, rs),
            scope: this
        };

        
        
        if (params.jsonData) {
            o.jsonData = params.jsonData;
        } else if (params.xmlData) {
            o.xmlData = params.xmlData;
        } else {
            o.params = params || {};
        }
        
        
        
        this.conn.url = this.buildUrl(action, rs);

        if(this.useAjax){

            Ext.applyIf(o, this.conn);

            
            if (this.activeRequest[action]) {
                
                
                
                
                
            }
            this.activeRequest[action] = Ext.Ajax.request(o);
        }else{
            this.conn.request(o);
        }
        
        this.conn.url = null;
    },

    
    createCallback : function(action, rs) {
        return function(o, success, response) {
            this.activeRequest[action] = undefined;
            if (!success) {
                if (action === Ext.data.Api.actions.read) {
                    
                    
                    this.fireEvent('loadexception', this, o, response);
                }
                this.fireEvent('exception', this, 'response', action, o, response);
                o.request.callback.call(o.request.scope, null, o.request.arg, false);
                return;
            }
            if (action === Ext.data.Api.actions.read) {
                this.onRead(action, o, response);
            } else {
                this.onWrite(action, o, response, rs);
            }
        };
    },

    
    onRead : function(action, o, response) {
        var result;
        try {
            result = o.reader.read(response);
        }catch(e){
            
            
            this.fireEvent('loadexception', this, o, response, e);

            this.fireEvent('exception', this, 'response', action, o, response, e);
            o.request.callback.call(o.request.scope, null, o.request.arg, false);
            return;
        }
        if (result.success === false) {
            
            
            this.fireEvent('loadexception', this, o, response);

            
            var res = o.reader.readResponse(action, response);
            this.fireEvent('exception', this, 'remote', action, o, res, null);
        }
        else {
            this.fireEvent('load', this, o, o.request.arg);
        }
        
        
        
        o.request.callback.call(o.request.scope, result, o.request.arg, result.success);
    },
    
    onWrite : function(action, o, response, rs) {
        var reader = o.reader;
        var res;
        try {
            res = reader.readResponse(action, response);
        } catch (e) {
            this.fireEvent('exception', this, 'response', action, o, response, e);
            o.request.callback.call(o.request.scope, null, o.request.arg, false);
            return;
        }
        if (res.success === true) {
            this.fireEvent('write', this, action, res.data, res, rs, o.request.arg);
        } else {
            this.fireEvent('exception', this, 'remote', action, o, res, rs);
        }
        
        
        
        o.request.callback.call(o.request.scope, res.data, res, res.success);
    },

    
    destroy: function(){
        if(!this.useAjax){
            this.conn.abort();
        }else if(this.activeRequest){
            var actions = Ext.data.Api.actions;
            for (var verb in actions) {
                if(this.activeRequest[actions[verb]]){
                    Ext.Ajax.abort(this.activeRequest[actions[verb]]);
                }
            }
        }
        Ext.data.HttpProxy.superclass.destroy.call(this);
    }
});
Ext.data.MemoryProxy = function(data){
    
    var api = {};
    api[Ext.data.Api.actions.read] = true;
    Ext.data.MemoryProxy.superclass.constructor.call(this, {
        api: api
    });
    this.data = data;
};

Ext.extend(Ext.data.MemoryProxy, Ext.data.DataProxy, {
    

       
    doRequest : function(action, rs, params, reader, callback, scope, arg) {
        
        params = params || {};
        var result;
        try {
            result = reader.readRecords(this.data);
        }catch(e){
            
            this.fireEvent("loadexception", this, null, arg, e);

            this.fireEvent('exception', this, 'response', action, arg, null, e);
            callback.call(scope, null, arg, false);
            return;
        }
        callback.call(scope, result, arg, true);
    }
});
Ext.data.Types = new function(){
    var st = Ext.data.SortTypes;
    Ext.apply(this, {
        
        stripRe: /[\$,%]/g,
        
        
        AUTO: {
            convert: function(v){ return v; },
            sortType: st.none,
            type: 'auto'
        },

        
        STRING: {
            convert: function(v){ return (v === undefined || v === null) ? '' : String(v); },
            sortType: st.asUCString,
            type: 'string'
        },

        
        INT: {
            convert: function(v){
                return v !== undefined && v !== null && v !== '' ?
                    parseInt(String(v).replace(Ext.data.Types.stripRe, ''), 10) : 0;
            },
            sortType: st.none,
            type: 'int'
        },
        
        
        FLOAT: {
            convert: function(v){
                return v !== undefined && v !== null && v !== '' ?
                    parseFloat(String(v).replace(Ext.data.Types.stripRe, ''), 10) : 0;
            },
            sortType: st.none,
            type: 'float'
        },
        
        
        BOOL: {
            convert: function(v){ return v === true || v === 'true' || v == 1; },
            sortType: st.none,
            type: 'bool'
        },
        
        
        DATE: {
            convert: function(v){
                var df = this.dateFormat;
                if(!v){
                    return null;
                }
                if(Ext.isDate(v)){
                    return v;
                }
                if(df){
                    if(df == 'timestamp'){
                        return new Date(v*1000);
                    }
                    if(df == 'time'){
                        return new Date(parseInt(v, 10));
                    }
                    return Date.parseDate(v, df);
                }
                var parsed = Date.parse(v);
                return parsed ? new Date(parsed) : null;
            },
            sortType: st.asDate,
            type: 'date'
        }
    });
    
    Ext.apply(this, {
        
        BOOLEAN: this.BOOL,
        
        INTEGER: this.INT,
        
        NUMBER: this.FLOAT    
    });
};
Ext.data.JsonWriter = Ext.extend(Ext.data.DataWriter, {
    
    encode : true,
    
    encodeDelete: false,
    
    constructor : function(config){
        Ext.data.JsonWriter.superclass.constructor.call(this, config);    
    },

    
    render : function(params, baseParams, data) {
        if (this.encode === true) {
            
            Ext.apply(params, baseParams);
            params[this.meta.root] = Ext.encode(data);
        } else {
            
            var jdata = Ext.apply({}, baseParams);
            jdata[this.meta.root] = data;
            params.jsonData = jdata;
        }
    },
    
    createRecord : function(rec) {
       return this.toHash(rec);
    },
    
    updateRecord : function(rec) {
        return this.toHash(rec);

    },
    
    destroyRecord : function(rec){
        if(this.encodeDelete){
            var data = {};
            data[this.meta.idProperty] = rec.id;
            return data;
        }else{
            return rec.id;
        }
    }
});
Ext.data.JsonReader = function(meta, recordType){
    meta = meta || {};
    
    
    
    
    Ext.applyIf(meta, {
        idProperty: 'id',
        successProperty: 'success',
        totalProperty: 'total'
    });

    Ext.data.JsonReader.superclass.constructor.call(this, meta, recordType || meta.fields);
};
Ext.extend(Ext.data.JsonReader, Ext.data.DataReader, {
    
    
    read : function(response){
        var json = response.responseText;
        var o = Ext.decode(json);
        if(!o) {
            throw {message: 'JsonReader.read: Json object not found'};
        }
        return this.readRecords(o);
    },

    
    
    readResponse : function(action, response) {
        var o = (response.responseText !== undefined) ? Ext.decode(response.responseText) : response;
        if(!o) {
            throw new Ext.data.JsonReader.Error('response');
        }

        var root = this.getRoot(o);
        if (action === Ext.data.Api.actions.create) {
            var def = Ext.isDefined(root);
            if (def && Ext.isEmpty(root)) {
                throw new Ext.data.JsonReader.Error('root-empty', this.meta.root);
            }
            else if (!def) {
                throw new Ext.data.JsonReader.Error('root-undefined-response', this.meta.root);
            }
        }

        
        var res = new Ext.data.Response({
            action: action,
            success: this.getSuccess(o),
            data: (root) ? this.extractData(root, false) : [],
            message: this.getMessage(o),
            raw: o
        });

        
        if (Ext.isEmpty(res.success)) {
            throw new Ext.data.JsonReader.Error('successProperty-response', this.meta.successProperty);
        }
        return res;
    },

    
    readRecords : function(o){
        
        this.jsonData = o;
        if(o.metaData){
            this.onMetaChange(o.metaData);
        }
        var s = this.meta, Record = this.recordType,
            f = Record.prototype.fields, fi = f.items, fl = f.length, v;

        var root = this.getRoot(o), c = root.length, totalRecords = c, success = true;
        if(s.totalProperty){
            v = parseInt(this.getTotal(o), 10);
            if(!isNaN(v)){
                totalRecords = v;
            }
        }
        if(s.successProperty){
            v = this.getSuccess(o);
            if(v === false || v === 'false'){
                success = false;
            }
        }

        
        return {
            success : success,
            records : this.extractData(root, true), 
            totalRecords : totalRecords
        };
    },

    
    buildExtractors : function() {
        if(this.ef){
            return;
        }
        var s = this.meta, Record = this.recordType,
            f = Record.prototype.fields, fi = f.items, fl = f.length;

        if(s.totalProperty) {
            this.getTotal = this.createAccessor(s.totalProperty);
        }
        if(s.successProperty) {
            this.getSuccess = this.createAccessor(s.successProperty);
        }
        if (s.messageProperty) {
            this.getMessage = this.createAccessor(s.messageProperty);
        }
        this.getRoot = s.root ? this.createAccessor(s.root) : function(p){return p;};
        if (s.id || s.idProperty) {
            var g = this.createAccessor(s.id || s.idProperty);
            this.getId = function(rec) {
                var r = g(rec);
                return (r === undefined || r === '') ? null : r;
            };
        } else {
            this.getId = function(){return null;};
        }
        var ef = [];
        for(var i = 0; i < fl; i++){
            f = fi[i];
            var map = (f.mapping !== undefined && f.mapping !== null) ? f.mapping : f.name;
            ef.push(this.createAccessor(map));
        }
        this.ef = ef;
    },

    
    simpleAccess : function(obj, subsc) {
        return obj[subsc];
    },

    
    createAccessor : function(){
        var re = /[\[\.]/;
        return function(expr) {
            if(Ext.isEmpty(expr)){
                return Ext.emptyFn;
            }
            if(Ext.isFunction(expr)){
                return expr;
            }
            var i = String(expr).search(re);
            if(i >= 0){
                return new Function('obj', 'return obj' + (i > 0 ? '.' : '') + expr);
            }
            return function(obj){
                return obj[expr];
            };

        };
    }(),

    
    extractValues : function(data, items, len) {
        var f, values = {};
        for(var j = 0; j < len; j++){
            f = items[j];
            var v = this.ef[j](data);
            values[f.name] = f.convert((v !== undefined) ? v : f.defaultValue, data);
        }
        return values;
    }
});


Ext.data.JsonReader.Error = Ext.extend(Ext.Error, {
    constructor : function(message, arg) {
        this.arg = arg;
        Ext.Error.call(this, message);
    },
    name : 'Ext.data.JsonReader'
});
Ext.apply(Ext.data.JsonReader.Error.prototype, {
    lang: {
        'response': 'An error occurred while json-decoding your server response',
        'successProperty-response': 'Could not locate your "successProperty" in your server response.  Please review your JsonReader config to ensure the config-property "successProperty" matches the property in your server-response.  See the JsonReader docs.',
        'root-undefined-config': 'Your JsonReader was configured without a "root" property.  Please review your JsonReader config and make sure to define the root property.  See the JsonReader docs.',
        'idProperty-undefined' : 'Your JsonReader was configured without an "idProperty"  Please review your JsonReader configuration and ensure the "idProperty" is set (e.g.: "id").  See the JsonReader docs.',
        'root-empty': 'Data was expected to be returned by the server in the "root" property of the response.  Please review your JsonReader configuration to ensure the "root" property matches that returned in the server-response.  See JsonReader docs.'
    }
});

Ext.data.ArrayReader = Ext.extend(Ext.data.JsonReader, {
    
    
    
    
    readRecords : function(o){
        this.arrayData = o;
        var s = this.meta,
            sid = s ? Ext.num(s.idIndex, s.id) : null,
            recordType = this.recordType,
            fields = recordType.prototype.fields,
            records = [],
            success = true,
            v;

        var root = this.getRoot(o);

        for(var i = 0, len = root.length; i < len; i++) {
            var n = root[i],
                values = {},
                id = ((sid || sid === 0) && n[sid] !== undefined && n[sid] !== "" ? n[sid] : null);
            for(var j = 0, jlen = fields.length; j < jlen; j++) {
                var f = fields.items[j],
                    k = f.mapping !== undefined && f.mapping !== null ? f.mapping : j;
                v = n[k] !== undefined ? n[k] : f.defaultValue;
                v = f.convert(v, n);
                values[f.name] = v;
            }
            var record = new recordType(values, id);
            record.json = n;
            records[records.length] = record;
        }

        var totalRecords = records.length;

        if(s.totalProperty) {
            v = parseInt(this.getTotal(o), 10);
            if(!isNaN(v)) {
                totalRecords = v;
            }
        }
        if(s.successProperty){
            v = this.getSuccess(o);
            if(v === false || v === 'false'){
                success = false;
            }
        }

        return {
            success : success,
            records : records,
            totalRecords : totalRecords
        };
    }
});
Ext.data.ArrayStore = Ext.extend(Ext.data.Store, {
    
    constructor: function(config){
        Ext.data.ArrayStore.superclass.constructor.call(this, Ext.apply(config, {
            reader: new Ext.data.ArrayReader(config)
        }));
    },

    loadData : function(data, append){
        if(this.expandData === true){
            var r = [];
            for(var i = 0, len = data.length; i < len; i++){
                r[r.length] = [data[i]];
            }
            data = r;
        }
        Ext.data.ArrayStore.superclass.loadData.call(this, data, append);
    }
});
Ext.reg('arraystore', Ext.data.ArrayStore);


Ext.data.SimpleStore = Ext.data.ArrayStore;
Ext.reg('simplestore', Ext.data.SimpleStore);
Ext.data.JsonStore = Ext.extend(Ext.data.Store, {
    
    constructor: function(config){
        Ext.data.JsonStore.superclass.constructor.call(this, Ext.apply(config, {
            reader: new Ext.data.JsonReader(config)
        }));
    }
});
Ext.reg('jsonstore', Ext.data.JsonStore);
Ext.data.XmlWriter = function(params) {
    Ext.data.XmlWriter.superclass.constructor.apply(this, arguments);
    
    this.tpl = (typeof(this.tpl) === 'string') ? new Ext.XTemplate(this.tpl).compile() : this.tpl.compile();
};
Ext.extend(Ext.data.XmlWriter, Ext.data.DataWriter, {
    
    documentRoot: 'xrequest',
    
    forceDocumentRoot: false,
    
    root: 'records',
    
    xmlVersion : '1.0',
    
    xmlEncoding: 'ISO-8859-15',
    
    
    tpl: '<tpl for="."><\u003fxml version="{version}" encoding="{encoding}"\u003f><tpl if="documentRoot"><{documentRoot}><tpl for="baseParams"><tpl for="."><{name}>{value}</{name}</tpl></tpl></tpl><tpl if="records.length&gt;1"><{root}></tpl><tpl for="records"><{parent.record}><tpl for="."><{name}>{value}</{name}></tpl></{parent.record}></tpl><tpl if="records.length&gt;1"></{root}></tpl><tpl if="documentRoot"></{documentRoot}></tpl></tpl>',


    
    render : function(params, baseParams, data) {
        baseParams = this.toArray(baseParams);
        params.xmlData = this.tpl.applyTemplate({
            version: this.xmlVersion,
            encoding: this.xmlEncoding,
            documentRoot: (baseParams.length > 0 || this.forceDocumentRoot === true) ? this.documentRoot : false,
            record: this.meta.record,
            root: this.root,
            baseParams: baseParams,
            records: (Ext.isArray(data[0])) ? data : [data]
        });
    },

    
    createRecord : function(rec) {
        return this.toArray(this.toHash(rec));
    },

    
    updateRecord : function(rec) {
        return this.toArray(this.toHash(rec));

    },
    
    destroyRecord : function(rec) {
        var data = {};
        data[this.meta.idProperty] = rec.id;
        return this.toArray(data);
    }
});

Ext.data.XmlReader = function(meta, recordType){
    meta = meta || {};

    
    Ext.applyIf(meta, {
        idProperty: meta.idProperty || meta.idPath || meta.id,
        successProperty: meta.successProperty || meta.success
    });

    Ext.data.XmlReader.superclass.constructor.call(this, meta, recordType || meta.fields);
};
Ext.extend(Ext.data.XmlReader, Ext.data.DataReader, {
    
    read : function(response){
        var doc = response.responseXML;
        if(!doc) {
            throw {message: "XmlReader.read: XML Document not available"};
        }
        return this.readRecords(doc);
    },

    
    readRecords : function(doc){
        
        this.xmlData = doc;

        var root    = doc.documentElement || doc,
            q       = Ext.DomQuery,
            totalRecords = 0,
            success = true;

        if(this.meta.totalProperty){
            totalRecords = this.getTotal(root, 0);
        }
        if(this.meta.successProperty){
            success = this.getSuccess(root);
        }

        var records = this.extractData(q.select(this.meta.record, root), true); 

        
        return {
            success : success,
            records : records,
            totalRecords : totalRecords || records.length
        };
    },

    
    readResponse : function(action, response) {
        var q   = Ext.DomQuery,
        doc     = response.responseXML;

        
        var res = new Ext.data.Response({
            action: action,
            success : this.getSuccess(doc),
            message: this.getMessage(doc),
            data: this.extractData(q.select(this.meta.record, doc) || q.select(this.meta.root, doc), false),
            raw: doc
        });

        if (Ext.isEmpty(res.success)) {
            throw new Ext.data.DataReader.Error('successProperty-response', this.meta.successProperty);
        }

        
        if (action === Ext.data.Api.actions.create) {
            var def = Ext.isDefined(res.data);
            if (def && Ext.isEmpty(res.data)) {
                throw new Ext.data.JsonReader.Error('root-empty', this.meta.root);
            }
            else if (!def) {
                throw new Ext.data.JsonReader.Error('root-undefined-response', this.meta.root);
            }
        }
        return res;
    },

    getSuccess : function() {
        return true;
    },

    
    buildExtractors : function() {
        if(this.ef){
            return;
        }
        var s       = this.meta,
            Record  = this.recordType,
            f       = Record.prototype.fields,
            fi      = f.items,
            fl      = f.length;

        if(s.totalProperty) {
            this.getTotal = this.createAccessor(s.totalProperty);
        }
        if(s.successProperty) {
            this.getSuccess = this.createAccessor(s.successProperty);
        }
        if (s.messageProperty) {
            this.getMessage = this.createAccessor(s.messageProperty);
        }
        this.getRoot = function(res) {
            return (!Ext.isEmpty(res[this.meta.record])) ? res[this.meta.record] : res[this.meta.root];
        };
        if (s.idPath || s.idProperty) {
            var g = this.createAccessor(s.idPath || s.idProperty);
            this.getId = function(rec) {
                var id = g(rec) || rec.id;
                return (id === undefined || id === '') ? null : id;
            };
        } else {
            this.getId = function(){return null;};
        }
        var ef = [];
        for(var i = 0; i < fl; i++){
            f = fi[i];
            var map = (f.mapping !== undefined && f.mapping !== null) ? f.mapping : f.name;
            ef.push(this.createAccessor(map));
        }
        this.ef = ef;
    },

    
    createAccessor : function(){
        var q = Ext.DomQuery;
        return function(key) {
            switch(key) {
                case this.meta.totalProperty:
                    return function(root, def){
                        return q.selectNumber(key, root, def);
                    };
                    break;
                case this.meta.successProperty:
                    return function(root, def) {
                        var sv = q.selectValue(key, root, true);
                        var success = sv !== false && sv !== 'false';
                        return success;
                    };
                    break;
                default:
                    return function(root, def) {
                        return q.selectValue(key, root, def);
                    };
                    break;
            }
        };
    }(),

    
    extractValues : function(data, items, len) {
        var f, values = {};
        for(var j = 0; j < len; j++){
            f = items[j];
            var v = this.ef[j](data);
            values[f.name] = f.convert((v !== undefined) ? v : f.defaultValue, data);
        }
        return values;
    }
});
Ext.data.XmlStore = Ext.extend(Ext.data.Store, {
    
    constructor: function(config){
        Ext.data.XmlStore.superclass.constructor.call(this, Ext.apply(config, {
            reader: new Ext.data.XmlReader(config)
        }));
    }
});
Ext.reg('xmlstore', Ext.data.XmlStore);
Ext.data.GroupingStore = Ext.extend(Ext.data.Store, {

    
    constructor: function(config) {
        config = config || {};

        
        
        
        
        this.hasMultiSort  = true;
        this.multiSortInfo = this.multiSortInfo || {sorters: []};

        var sorters    = this.multiSortInfo.sorters,
            groupField = config.groupField || this.groupField,
            sortInfo   = config.sortInfo || this.sortInfo,
            groupDir   = config.groupDir || this.groupDir;

        
        if(groupField){
            sorters.push({
                field    : groupField,
                direction: groupDir
            });
        }

        
        if (sortInfo) {
            sorters.push(sortInfo);
        }

        Ext.data.GroupingStore.superclass.constructor.call(this, config);

        this.addEvents(
          
          'groupchange'
        );

        this.applyGroupField();
    },

    
    
    remoteGroup : false,
    
    groupOnSort:false,

    groupDir : 'ASC',

    
    clearGrouping : function(){
        this.groupField = false;

        if(this.remoteGroup){
            if(this.baseParams){
                delete this.baseParams.groupBy;
                delete this.baseParams.groupDir;
            }
            var lo = this.lastOptions;
            if(lo && lo.params){
                delete lo.params.groupBy;
                delete lo.params.groupDir;
            }

            this.reload();
        }else{
            this.sort();
            this.fireEvent('datachanged', this);
        }
    },

    
    groupBy : function(field, forceRegroup, direction) {
        direction = direction ? (String(direction).toUpperCase() == 'DESC' ? 'DESC' : 'ASC') : this.groupDir;

        if (this.groupField == field && this.groupDir == direction && !forceRegroup) {
            return; 
        }

        
        
        sorters = this.multiSortInfo.sorters;
        if (sorters.length > 0 && sorters[0].field == this.groupField) {
            sorters.shift();
        }

        this.groupField = field;
        this.groupDir = direction;
        this.applyGroupField();

        var fireGroupEvent = function() {
            this.fireEvent('groupchange', this, this.getGroupState());
        };

        if (this.groupOnSort) {
            this.sort(field, direction);
            fireGroupEvent.call(this);
            return;
        }

        if (this.remoteGroup) {
            this.on('load', fireGroupEvent, this, {single: true});
            this.reload();
        } else {
            this.sort(sorters);
            fireGroupEvent.call(this);
        }
    },

    
    
    sort : function(fieldName, dir) {
        if (this.remoteSort) {
            return Ext.data.GroupingStore.superclass.sort.call(this, fieldName, dir);
        }

        var sorters = [];

        
        if (Ext.isArray(arguments[0])) {
            sorters = arguments[0];
        } else if (fieldName == undefined) {
            
            
            sorters = this.sortInfo ? [this.sortInfo] : [];
        } else {
            
            
            var field = this.fields.get(fieldName);
            if (!field) return false;

            var name       = field.name,
                sortInfo   = this.sortInfo || null,
                sortToggle = this.sortToggle ? this.sortToggle[name] : null;

            if (!dir) {
                if (sortInfo && sortInfo.field == name) { 
                    dir = (this.sortToggle[name] || 'ASC').toggle('ASC', 'DESC');
                } else {
                    dir = field.sortDir;
                }
            }

            this.sortToggle[name] = dir;
            this.sortInfo = {field: name, direction: dir};

            sorters = [this.sortInfo];
        }

        
        if (this.groupField) {
            sorters.unshift({direction: this.groupDir, field: this.groupField});
        }

        return this.multiSort.call(this, sorters, dir);
    },

    
    applyGroupField: function(){
        if (this.remoteGroup) {
            if(!this.baseParams){
                this.baseParams = {};
            }

            Ext.apply(this.baseParams, {
                groupBy : this.groupField,
                groupDir: this.groupDir
            });

            var lo = this.lastOptions;
            if (lo && lo.params) {
                lo.params.groupDir = this.groupDir;

                
                delete lo.params.groupBy;
            }
        }
    },

    
    applyGrouping : function(alwaysFireChange){
        if(this.groupField !== false){
            this.groupBy(this.groupField, true, this.groupDir);
            return true;
        }else{
            if(alwaysFireChange === true){
                this.fireEvent('datachanged', this);
            }
            return false;
        }
    },

    
    getGroupState : function(){
        return this.groupOnSort && this.groupField !== false ?
               (this.sortInfo ? this.sortInfo.field : undefined) : this.groupField;
    }
});
Ext.reg('groupingstore', Ext.data.GroupingStore);

Ext.data.DirectProxy = function(config){
    Ext.apply(this, config);
    if(typeof this.paramOrder == 'string'){
        this.paramOrder = this.paramOrder.split(/[\s,|]/);
    }
    Ext.data.DirectProxy.superclass.constructor.call(this, config);
};

Ext.extend(Ext.data.DirectProxy, Ext.data.DataProxy, {
    
    paramOrder: undefined,

    
    paramsAsHash: true,

    
    directFn : undefined,

    
    doRequest : function(action, rs, params, reader, callback, scope, options) {
        var args = [],
            directFn = this.api[action] || this.directFn;

        switch (action) {
            case Ext.data.Api.actions.create:
                args.push(params.jsonData);		
                break;
            case Ext.data.Api.actions.read:
                
                if(directFn.directCfg.method.len > 0){
                    if(this.paramOrder){
                        for(var i = 0, len = this.paramOrder.length; i < len; i++){
                            args.push(params[this.paramOrder[i]]);
                        }
                    }else if(this.paramsAsHash){
                        args.push(params);
                    }
                }
                break;
            case Ext.data.Api.actions.update:
                args.push(params.jsonData);        
                break;
            case Ext.data.Api.actions.destroy:
                args.push(params.jsonData);        
                break;
        }

        var trans = {
            params : params || {},
            request: {
                callback : callback,
                scope : scope,
                arg : options
            },
            reader: reader
        };

        args.push(this.createCallback(action, rs, trans), this);
        directFn.apply(window, args);
    },

    
    createCallback : function(action, rs, trans) {
        var me = this;
        return function(result, res) {
            if (!res.status) {
                
                if (action === Ext.data.Api.actions.read) {
                    me.fireEvent("loadexception", me, trans, res, null);
                }
                me.fireEvent('exception', me, 'remote', action, trans, res, null);
                trans.request.callback.call(trans.request.scope, null, trans.request.arg, false);
                return;
            }
            if (action === Ext.data.Api.actions.read) {
                me.onRead(action, trans, result, res);
            } else {
                me.onWrite(action, trans, result, res, rs);
            }
        };
    },

    
    onRead : function(action, trans, result, res) {
        var records;
        try {
            records = trans.reader.readRecords(result);
        }
        catch (ex) {
            
            this.fireEvent("loadexception", this, trans, res, ex);

            this.fireEvent('exception', this, 'response', action, trans, res, ex);
            trans.request.callback.call(trans.request.scope, null, trans.request.arg, false);
            return;
        }
        this.fireEvent("load", this, res, trans.request.arg);
        trans.request.callback.call(trans.request.scope, records, trans.request.arg, true);
    },
    
    onWrite : function(action, trans, result, res, rs) {
        var data = trans.reader.extractData(trans.reader.getRoot(result), false);
        var success = trans.reader.getSuccess(result);
        success = (success !== false);
        if (success){
            this.fireEvent("write", this, action, data, res, rs, trans.request.arg);
        }else{
            this.fireEvent('exception', this, 'remote', action, trans, result, rs);
        }
        trans.request.callback.call(trans.request.scope, data, res, success);
    }
});

Ext.data.DirectStore = Ext.extend(Ext.data.Store, {
    constructor : function(config){
        
        var c = Ext.apply({}, {
            batchTransactions: false
        }, config);
        Ext.data.DirectStore.superclass.constructor.call(this, Ext.apply(c, {
            proxy: Ext.isDefined(c.proxy) ? c.proxy : new Ext.data.DirectProxy(Ext.copyTo({}, c, 'paramOrder,paramsAsHash,directFn,api')),
            reader: (!Ext.isDefined(c.reader) && c.fields) ? new Ext.data.JsonReader(Ext.copyTo({}, c, 'totalProperty,root,idProperty'), c.fields) : c.reader
        }));
    }
});
Ext.reg('directstore', Ext.data.DirectStore);

Ext.Direct = Ext.extend(Ext.util.Observable, {
    

    
    exceptions: {
        TRANSPORT: 'xhr',
        PARSE: 'parse',
        LOGIN: 'login',
        SERVER: 'exception'
    },

    
    constructor: function(){
        this.addEvents(
            
            'event',
            
            'exception'
        );
        this.transactions = {};
        this.providers = {};
    },

    
    addProvider : function(provider){
        var a = arguments;
        if(a.length > 1){
            for(var i = 0, len = a.length; i < len; i++){
                this.addProvider(a[i]);
            }
            return;
        }

        
        if(!provider.events){
            provider = new Ext.Direct.PROVIDERS[provider.type](provider);
        }
        provider.id = provider.id || Ext.id();
        this.providers[provider.id] = provider;

        provider.on('data', this.onProviderData, this);
        provider.on('exception', this.onProviderException, this);


        if(!provider.isConnected()){
            provider.connect();
        }

        return provider;
    },

    
    getProvider : function(id){
        return this.providers[id];
    },

    removeProvider : function(id){
        var provider = id.id ? id : this.providers[id];
        provider.un('data', this.onProviderData, this);
        provider.un('exception', this.onProviderException, this);
        delete this.providers[provider.id];
        return provider;
    },

    addTransaction: function(t){
        this.transactions[t.tid] = t;
        return t;
    },

    removeTransaction: function(t){
        delete this.transactions[t.tid || t];
        return t;
    },

    getTransaction: function(tid){
        return this.transactions[tid.tid || tid];
    },

    onProviderData : function(provider, e){
        if(Ext.isArray(e)){
            for(var i = 0, len = e.length; i < len; i++){
                this.onProviderData(provider, e[i]);
            }
            return;
        }
        if(e.name && e.name != 'event' && e.name != 'exception'){
            this.fireEvent(e.name, e);
        }else if(e.type == 'exception'){
            this.fireEvent('exception', e);
        }
        this.fireEvent('event', e, provider);
    },

    createEvent : function(response, extraProps){
        return new Ext.Direct.eventTypes[response.type](Ext.apply(response, extraProps));
    }
});

Ext.Direct = new Ext.Direct();

Ext.Direct.TID = 1;
Ext.Direct.PROVIDERS = {};
Ext.Direct.Transaction = function(config){
    Ext.apply(this, config);
    this.tid = ++Ext.Direct.TID;
    this.retryCount = 0;
};
Ext.Direct.Transaction.prototype = {
    send: function(){
        this.provider.queueTransaction(this);
    },

    retry: function(){
        this.retryCount++;
        this.send();
    },

    getProvider: function(){
        return this.provider;
    }
};Ext.Direct.Event = function(config){
    Ext.apply(this, config);
};

Ext.Direct.Event.prototype = {
    status: true,
    getData: function(){
        return this.data;
    }
};

Ext.Direct.RemotingEvent = Ext.extend(Ext.Direct.Event, {
    type: 'rpc',
    getTransaction: function(){
        return this.transaction || Ext.Direct.getTransaction(this.tid);
    }
});

Ext.Direct.ExceptionEvent = Ext.extend(Ext.Direct.RemotingEvent, {
    status: false,
    type: 'exception'
});

Ext.Direct.eventTypes = {
    'rpc':  Ext.Direct.RemotingEvent,
    'event':  Ext.Direct.Event,
    'exception':  Ext.Direct.ExceptionEvent
};

Ext.direct.Provider = Ext.extend(Ext.util.Observable, {    
    
        
        
    priority: 1,

        
 
    
    constructor : function(config){
        Ext.apply(this, config);
        this.addEvents(
                        
            'connect',
                        
            'disconnect',
                        
            'data',
                                    
            'exception'
        );
        Ext.direct.Provider.superclass.constructor.call(this, config);
    },

    
    isConnected: function(){
        return false;
    },

    
    connect: Ext.emptyFn,
    
    
    disconnect: Ext.emptyFn
});

Ext.direct.JsonProvider = Ext.extend(Ext.direct.Provider, {
    parseResponse: function(xhr){
        if(!Ext.isEmpty(xhr.responseText)){
            if(typeof xhr.responseText == 'object'){
                return xhr.responseText;
            }
            return Ext.decode(xhr.responseText);
        }
        return null;
    },

    getEvents: function(xhr){
        var data = null;
        try{
            data = this.parseResponse(xhr);
        }catch(e){
            var event = new Ext.Direct.ExceptionEvent({
                data: e,
                xhr: xhr,
                code: Ext.Direct.exceptions.PARSE,
                message: 'Error parsing json response: \n\n ' + data
            });
            return [event];
        }
        var events = [];
        if(Ext.isArray(data)){
            for(var i = 0, len = data.length; i < len; i++){
                events.push(Ext.Direct.createEvent(data[i]));
            }
        }else{
            events.push(Ext.Direct.createEvent(data));
        }
        return events;
    }
});
Ext.direct.PollingProvider = Ext.extend(Ext.direct.JsonProvider, {
    
    
    priority: 3,
    
    
    interval: 3000,

    
    
    

    
    constructor : function(config){
        Ext.direct.PollingProvider.superclass.constructor.call(this, config);
        this.addEvents(
            
            'beforepoll',            
            
            'poll'
        );
    },

    
    isConnected: function(){
        return !!this.pollTask;
    },

    
    connect: function(){
        if(this.url && !this.pollTask){
            this.pollTask = Ext.TaskMgr.start({
                run: function(){
                    if(this.fireEvent('beforepoll', this) !== false){
                        if(typeof this.url == 'function'){
                            this.url(this.baseParams);
                        }else{
                            Ext.Ajax.request({
                                url: this.url,
                                callback: this.onData,
                                scope: this,
                                params: this.baseParams
                            });
                        }
                    }
                },
                interval: this.interval,
                scope: this
            });
            this.fireEvent('connect', this);
        }else if(!this.url){
            throw 'Error initializing PollingProvider, no url configured.';
        }
    },

    
    disconnect: function(){
        if(this.pollTask){
            Ext.TaskMgr.stop(this.pollTask);
            delete this.pollTask;
            this.fireEvent('disconnect', this);
        }
    },

    
    onData: function(opt, success, xhr){
        if(success){
            var events = this.getEvents(xhr);
            for(var i = 0, len = events.length; i < len; i++){
                var e = events[i];
                this.fireEvent('data', this, e);
            }
        }else{
            var e = new Ext.Direct.ExceptionEvent({
                data: e,
                code: Ext.Direct.exceptions.TRANSPORT,
                message: 'Unable to connect to the server.',
                xhr: xhr
            });
            this.fireEvent('data', this, e);
        }
    }
});

Ext.Direct.PROVIDERS['polling'] = Ext.direct.PollingProvider;
Ext.direct.RemotingProvider = Ext.extend(Ext.direct.JsonProvider, {       
    
    
    
    
    
    
    
    
    
    enableBuffer: 10,
    
    
    maxRetries: 1,
    
    
    timeout: undefined,

    constructor : function(config){
        Ext.direct.RemotingProvider.superclass.constructor.call(this, config);
        this.addEvents(
                        
            'beforecall',            
                        
            'call'
        );
        this.namespace = (Ext.isString(this.namespace)) ? Ext.ns(this.namespace) : this.namespace || window;
        this.transactions = {};
        this.callBuffer = [];
    },

    
    initAPI : function(){
        var o = this.actions;
        for(var c in o){
            var cls = this.namespace[c] || (this.namespace[c] = {}),
                ms = o[c];
            for(var i = 0, len = ms.length; i < len; i++){
                var m = ms[i];
                cls[m.name] = this.createMethod(c, m);
            }
        }
    },

    
    isConnected: function(){
        return !!this.connected;
    },

    connect: function(){
        if(this.url){
            this.initAPI();
            this.connected = true;
            this.fireEvent('connect', this);
        }else if(!this.url){
            throw 'Error initializing RemotingProvider, no url configured.';
        }
    },

    disconnect: function(){
        if(this.connected){
            this.connected = false;
            this.fireEvent('disconnect', this);
        }
    },

    onData: function(opt, success, xhr){
        if(success){
            var events = this.getEvents(xhr);
            for(var i = 0, len = events.length; i < len; i++){
                var e = events[i],
                    t = this.getTransaction(e);
                this.fireEvent('data', this, e);
                if(t){
                    this.doCallback(t, e, true);
                    Ext.Direct.removeTransaction(t);
                }
            }
        }else{
            var ts = [].concat(opt.ts);
            for(var i = 0, len = ts.length; i < len; i++){
                var t = this.getTransaction(ts[i]);
                if(t && t.retryCount < this.maxRetries){
                    t.retry();
                }else{
                    var e = new Ext.Direct.ExceptionEvent({
                        data: e,
                        transaction: t,
                        code: Ext.Direct.exceptions.TRANSPORT,
                        message: 'Unable to connect to the server.',
                        xhr: xhr
                    });
                    this.fireEvent('data', this, e);
                    if(t){
                        this.doCallback(t, e, false);
                        Ext.Direct.removeTransaction(t);
                    }
                }
            }
        }
    },

    getCallData: function(t){
        return {
            action: t.action,
            method: t.method,
            data: t.data,
            type: 'rpc',
            tid: t.tid
        };
    },

    doSend : function(data){
        var o = {
            url: this.url,
            callback: this.onData,
            scope: this,
            ts: data,
            timeout: this.timeout
        }, callData;

        if(Ext.isArray(data)){
            callData = [];
            for(var i = 0, len = data.length; i < len; i++){
                callData.push(this.getCallData(data[i]));
            }
        }else{
            callData = this.getCallData(data);
        }

        if(this.enableUrlEncode){
            var params = {};
            params[Ext.isString(this.enableUrlEncode) ? this.enableUrlEncode : 'data'] = Ext.encode(callData);
            o.params = params;
        }else{
            o.jsonData = callData;
        }
        Ext.Ajax.request(o);
    },

    combineAndSend : function(){
        var len = this.callBuffer.length;
        if(len > 0){
            this.doSend(len == 1 ? this.callBuffer[0] : this.callBuffer);
            this.callBuffer = [];
        }
    },

    queueTransaction: function(t){
        if(t.form){
            this.processForm(t);
            return;
        }
        this.callBuffer.push(t);
        if(this.enableBuffer){
            if(!this.callTask){
                this.callTask = new Ext.util.DelayedTask(this.combineAndSend, this);
            }
            this.callTask.delay(Ext.isNumber(this.enableBuffer) ? this.enableBuffer : 10);
        }else{
            this.combineAndSend();
        }
    },

    doCall : function(c, m, args){
        var data = null, hs = args[m.len], scope = args[m.len+1];

        if(m.len !== 0){
            data = args.slice(0, m.len);
        }

        var t = new Ext.Direct.Transaction({
            provider: this,
            args: args,
            action: c,
            method: m.name,
            data: data,
            cb: scope && Ext.isFunction(hs) ? hs.createDelegate(scope) : hs
        });

        if(this.fireEvent('beforecall', this, t) !== false){
            Ext.Direct.addTransaction(t);
            this.queueTransaction(t);
            this.fireEvent('call', this, t);
        }
    },

    doForm : function(c, m, form, callback, scope){
        var t = new Ext.Direct.Transaction({
            provider: this,
            action: c,
            method: m.name,
            args:[form, callback, scope],
            cb: scope && Ext.isFunction(callback) ? callback.createDelegate(scope) : callback,
            isForm: true
        });

        if(this.fireEvent('beforecall', this, t) !== false){
            Ext.Direct.addTransaction(t);
            var isUpload = String(form.getAttribute("enctype")).toLowerCase() == 'multipart/form-data',
                params = {
                    extTID: t.tid,
                    extAction: c,
                    extMethod: m.name,
                    extType: 'rpc',
                    extUpload: String(isUpload)
                };
            
            
            
            Ext.apply(t, {
                form: Ext.getDom(form),
                isUpload: isUpload,
                params: callback && Ext.isObject(callback.params) ? Ext.apply(params, callback.params) : params
            });
            this.fireEvent('call', this, t);
            this.processForm(t);
        }
    },
    
    processForm: function(t){
        Ext.Ajax.request({
            url: this.url,
            params: t.params,
            callback: this.onData,
            scope: this,
            form: t.form,
            isUpload: t.isUpload,
            ts: t
        });
    },

    createMethod : function(c, m){
        var f;
        if(!m.formHandler){
            f = function(){
                this.doCall(c, m, Array.prototype.slice.call(arguments, 0));
            }.createDelegate(this);
        }else{
            f = function(form, callback, scope){
                this.doForm(c, m, form, callback, scope);
            }.createDelegate(this);
        }
        f.directCfg = {
            action: c,
            method: m
        };
        return f;
    },

    getTransaction: function(opt){
        return opt && opt.tid ? Ext.Direct.getTransaction(opt.tid) : null;
    },

    doCallback: function(t, e){
        var fn = e.status ? 'success' : 'failure';
        if(t && t.cb){
            var hs = t.cb,
                result = Ext.isDefined(e.result) ? e.result : e.data;
            if(Ext.isFunction(hs)){
                hs(result, e);
            } else{
                Ext.callback(hs[fn], hs.scope, [result, e]);
                Ext.callback(hs.callback, hs.scope, [result, e]);
            }
        }
    }
});
Ext.Direct.PROVIDERS['remoting'] = Ext.direct.RemotingProvider;
Ext.Resizable = Ext.extend(Ext.util.Observable, {

    constructor: function(el, config){
        this.el = Ext.get(el);
        if(config && config.wrap){
            config.resizeChild = this.el;
            this.el = this.el.wrap(typeof config.wrap == 'object' ? config.wrap : {cls:'xresizable-wrap'});
            this.el.id = this.el.dom.id = config.resizeChild.id + '-rzwrap';
            this.el.setStyle('overflow', 'hidden');
            this.el.setPositioning(config.resizeChild.getPositioning());
            config.resizeChild.clearPositioning();
            if(!config.width || !config.height){
                var csize = config.resizeChild.getSize();
                this.el.setSize(csize.width, csize.height);
            }
            if(config.pinned && !config.adjustments){
                config.adjustments = 'auto';
            }
        }

        
        this.proxy = this.el.createProxy({tag: 'div', cls: 'x-resizable-proxy', id: this.el.id + '-rzproxy'}, Ext.getBody());
        this.proxy.unselectable();
        this.proxy.enableDisplayMode('block');

        Ext.apply(this, config);

        if(this.pinned){
            this.disableTrackOver = true;
            this.el.addClass('x-resizable-pinned');
        }
        
        var position = this.el.getStyle('position');
        if(position != 'absolute' && position != 'fixed'){
            this.el.setStyle('position', 'relative');
        }
        if(!this.handles){ 
            this.handles = 's,e,se';
            if(this.multiDirectional){
                this.handles += ',n,w';
            }
        }
        if(this.handles == 'all'){
            this.handles = 'n s e w ne nw se sw';
        }
        var hs = this.handles.split(/\s*?[,;]\s*?| /);
        var ps = Ext.Resizable.positions;
        for(var i = 0, len = hs.length; i < len; i++){
            if(hs[i] && ps[hs[i]]){
                var pos = ps[hs[i]];
                this[pos] = new Ext.Resizable.Handle(this, pos, this.disableTrackOver, this.transparent, this.handleCls);
            }
        }
        
        this.corner = this.southeast;

        if(this.handles.indexOf('n') != -1 || this.handles.indexOf('w') != -1){
            this.updateBox = true;
        }

        this.activeHandle = null;

        if(this.resizeChild){
            if(typeof this.resizeChild == 'boolean'){
                this.resizeChild = Ext.get(this.el.dom.firstChild, true);
            }else{
                this.resizeChild = Ext.get(this.resizeChild, true);
            }
        }

        if(this.adjustments == 'auto'){
            var rc = this.resizeChild;
            var hw = this.west, he = this.east, hn = this.north, hs = this.south;
            if(rc && (hw || hn)){
                rc.position('relative');
                rc.setLeft(hw ? hw.el.getWidth() : 0);
                rc.setTop(hn ? hn.el.getHeight() : 0);
            }
            this.adjustments = [
                (he ? -he.el.getWidth() : 0) + (hw ? -hw.el.getWidth() : 0),
                (hn ? -hn.el.getHeight() : 0) + (hs ? -hs.el.getHeight() : 0) -1
            ];
        }

        if(this.draggable){
            this.dd = this.dynamic ?
                this.el.initDD(null) : this.el.initDDProxy(null, {dragElId: this.proxy.id});
            this.dd.setHandleElId(this.resizeChild ? this.resizeChild.id : this.el.id);
            if(this.constrainTo){
                this.dd.constrainTo(this.constrainTo);
            }
        }

        this.addEvents(
            
            'beforeresize',
            
            'resize'
        );

        if(this.width !== null && this.height !== null){
            this.resizeTo(this.width, this.height);
        }else{
            this.updateChildSize();
        }
        if(Ext.isIE){
            this.el.dom.style.zoom = 1;
        }
        Ext.Resizable.superclass.constructor.call(this);
    },

    
    adjustments : [0, 0],
    
    animate : false,
    
    
    disableTrackOver : false,
    
    draggable: false,
    
    duration : 0.35,
    
    dynamic : false,
    
    easing : 'easeOutStrong',
    
    enabled : true,
    
    
    handles : false,
    
    multiDirectional : false,
    
    height : null,
    
    width : null,
    
    heightIncrement : 0,
    
    widthIncrement : 0,
    
    minHeight : 5,
    
    minWidth : 5,
    
    maxHeight : 10000,
    
    maxWidth : 10000,
    
    minX: 0,
    
    minY: 0,
    
    pinned : false,
    
    preserveRatio : false,
    
    resizeChild : false,
    
    transparent: false,
    
    
    


    
    resizeTo : function(width, height){
        this.el.setSize(width, height);
        this.updateChildSize();
        this.fireEvent('resize', this, width, height, null);
    },

    
    startSizing : function(e, handle){
        this.fireEvent('beforeresize', this, e);
        if(this.enabled){ 

            if(!this.overlay){
                this.overlay = this.el.createProxy({tag: 'div', cls: 'x-resizable-overlay', html: '&#160;'}, Ext.getBody());
                this.overlay.unselectable();
                this.overlay.enableDisplayMode('block');
                this.overlay.on({
                    scope: this,
                    mousemove: this.onMouseMove,
                    mouseup: this.onMouseUp
                });
            }
            this.overlay.setStyle('cursor', handle.el.getStyle('cursor'));

            this.resizing = true;
            this.startBox = this.el.getBox();
            this.startPoint = e.getXY();
            this.offsets = [(this.startBox.x + this.startBox.width) - this.startPoint[0],
                            (this.startBox.y + this.startBox.height) - this.startPoint[1]];

            this.overlay.setSize(Ext.lib.Dom.getViewWidth(true), Ext.lib.Dom.getViewHeight(true));
            this.overlay.show();

            if(this.constrainTo) {
                var ct = Ext.get(this.constrainTo);
                this.resizeRegion = ct.getRegion().adjust(
                    ct.getFrameWidth('t'),
                    ct.getFrameWidth('l'),
                    -ct.getFrameWidth('b'),
                    -ct.getFrameWidth('r')
                );
            }

            this.proxy.setStyle('visibility', 'hidden'); 
            this.proxy.show();
            this.proxy.setBox(this.startBox);
            if(!this.dynamic){
                this.proxy.setStyle('visibility', 'visible');
            }
        }
    },

    
    onMouseDown : function(handle, e){
        if(this.enabled){
            e.stopEvent();
            this.activeHandle = handle;
            this.startSizing(e, handle);
        }
    },

    
    onMouseUp : function(e){
        this.activeHandle = null;
        var size = this.resizeElement();
        this.resizing = false;
        this.handleOut();
        this.overlay.hide();
        this.proxy.hide();
        this.fireEvent('resize', this, size.width, size.height, e);
    },

    
    updateChildSize : function(){
        if(this.resizeChild){
            var el = this.el;
            var child = this.resizeChild;
            var adj = this.adjustments;
            if(el.dom.offsetWidth){
                var b = el.getSize(true);
                child.setSize(b.width+adj[0], b.height+adj[1]);
            }
            
            
            
            
            if(Ext.isIE){
                setTimeout(function(){
                    if(el.dom.offsetWidth){
                        var b = el.getSize(true);
                        child.setSize(b.width+adj[0], b.height+adj[1]);
                    }
                }, 10);
            }
        }
    },

    
    snap : function(value, inc, min){
        if(!inc || !value){
            return value;
        }
        var newValue = value;
        var m = value % inc;
        if(m > 0){
            if(m > (inc/2)){
                newValue = value + (inc-m);
            }else{
                newValue = value - m;
            }
        }
        return Math.max(min, newValue);
    },

    
    resizeElement : function(){
        var box = this.proxy.getBox();
        if(this.updateBox){
            this.el.setBox(box, false, this.animate, this.duration, null, this.easing);
        }else{
            this.el.setSize(box.width, box.height, this.animate, this.duration, null, this.easing);
        }
        this.updateChildSize();
        if(!this.dynamic){
            this.proxy.hide();
        }
        if(this.draggable && this.constrainTo){
            this.dd.resetConstraints();
            this.dd.constrainTo(this.constrainTo);
        }
        return box;
    },

    
    constrain : function(v, diff, m, mx){
        if(v - diff < m){
            diff = v - m;
        }else if(v - diff > mx){
            diff = v - mx;
        }
        return diff;
    },

    
    onMouseMove : function(e){
        if(this.enabled && this.activeHandle){
            try{

            if(this.resizeRegion && !this.resizeRegion.contains(e.getPoint())) {
                return;
            }

            
            var curSize = this.curSize || this.startBox,
                x = this.startBox.x, y = this.startBox.y,
                ox = x,
                oy = y,
                w = curSize.width,
                h = curSize.height,
                ow = w,
                oh = h,
                mw = this.minWidth,
                mh = this.minHeight,
                mxw = this.maxWidth,
                mxh = this.maxHeight,
                wi = this.widthIncrement,
                hi = this.heightIncrement,
                eventXY = e.getXY(),
                diffX = -(this.startPoint[0] - Math.max(this.minX, eventXY[0])),
                diffY = -(this.startPoint[1] - Math.max(this.minY, eventXY[1])),
                pos = this.activeHandle.position,
                tw,
                th;

            switch(pos){
                case 'east':
                    w += diffX;
                    w = Math.min(Math.max(mw, w), mxw);
                    break;
                case 'south':
                    h += diffY;
                    h = Math.min(Math.max(mh, h), mxh);
                    break;
                case 'southeast':
                    w += diffX;
                    h += diffY;
                    w = Math.min(Math.max(mw, w), mxw);
                    h = Math.min(Math.max(mh, h), mxh);
                    break;
                case 'north':
                    diffY = this.constrain(h, diffY, mh, mxh);
                    y += diffY;
                    h -= diffY;
                    break;
                case 'west':
                    diffX = this.constrain(w, diffX, mw, mxw);
                    x += diffX;
                    w -= diffX;
                    break;
                case 'northeast':
                    w += diffX;
                    w = Math.min(Math.max(mw, w), mxw);
                    diffY = this.constrain(h, diffY, mh, mxh);
                    y += diffY;
                    h -= diffY;
                    break;
                case 'northwest':
                    diffX = this.constrain(w, diffX, mw, mxw);
                    diffY = this.constrain(h, diffY, mh, mxh);
                    y += diffY;
                    h -= diffY;
                    x += diffX;
                    w -= diffX;
                    break;
               case 'southwest':
                    diffX = this.constrain(w, diffX, mw, mxw);
                    h += diffY;
                    h = Math.min(Math.max(mh, h), mxh);
                    x += diffX;
                    w -= diffX;
                    break;
            }

            var sw = this.snap(w, wi, mw);
            var sh = this.snap(h, hi, mh);
            if(sw != w || sh != h){
                switch(pos){
                    case 'northeast':
                        y -= sh - h;
                    break;
                    case 'north':
                        y -= sh - h;
                        break;
                    case 'southwest':
                        x -= sw - w;
                    break;
                    case 'west':
                        x -= sw - w;
                        break;
                    case 'northwest':
                        x -= sw - w;
                        y -= sh - h;
                    break;
                }
                w = sw;
                h = sh;
            }

            if(this.preserveRatio){
                switch(pos){
                    case 'southeast':
                    case 'east':
                        h = oh * (w/ow);
                        h = Math.min(Math.max(mh, h), mxh);
                        w = ow * (h/oh);
                       break;
                    case 'south':
                        w = ow * (h/oh);
                        w = Math.min(Math.max(mw, w), mxw);
                        h = oh * (w/ow);
                        break;
                    case 'northeast':
                        w = ow * (h/oh);
                        w = Math.min(Math.max(mw, w), mxw);
                        h = oh * (w/ow);
                    break;
                    case 'north':
                        tw = w;
                        w = ow * (h/oh);
                        w = Math.min(Math.max(mw, w), mxw);
                        h = oh * (w/ow);
                        x += (tw - w) / 2;
                        break;
                    case 'southwest':
                        h = oh * (w/ow);
                        h = Math.min(Math.max(mh, h), mxh);
                        tw = w;
                        w = ow * (h/oh);
                        x += tw - w;
                        break;
                    case 'west':
                        th = h;
                        h = oh * (w/ow);
                        h = Math.min(Math.max(mh, h), mxh);
                        y += (th - h) / 2;
                        tw = w;
                        w = ow * (h/oh);
                        x += tw - w;
                       break;
                    case 'northwest':
                        tw = w;
                        th = h;
                        h = oh * (w/ow);
                        h = Math.min(Math.max(mh, h), mxh);
                        w = ow * (h/oh);
                        y += th - h;
                        x += tw - w;
                        break;

                }
            }
            this.proxy.setBounds(x, y, w, h);
            if(this.dynamic){
                this.resizeElement();
            }
            }catch(ex){}
        }
    },

    
    handleOver : function(){
        if(this.enabled){
            this.el.addClass('x-resizable-over');
        }
    },

    
    handleOut : function(){
        if(!this.resizing){
            this.el.removeClass('x-resizable-over');
        }
    },

    
    getEl : function(){
        return this.el;
    },

    
    getResizeChild : function(){
        return this.resizeChild;
    },

    
    destroy : function(removeEl){
        Ext.destroy(this.dd, this.overlay, this.proxy);
        this.overlay = null;
        this.proxy = null;

        var ps = Ext.Resizable.positions;
        for(var k in ps){
            if(typeof ps[k] != 'function' && this[ps[k]]){
                this[ps[k]].destroy();
            }
        }
        if(removeEl){
            this.el.update('');
            Ext.destroy(this.el);
            this.el = null;
        }
        this.purgeListeners();
    },

    syncHandleHeight : function(){
        var h = this.el.getHeight(true);
        if(this.west){
            this.west.el.setHeight(h);
        }
        if(this.east){
            this.east.el.setHeight(h);
        }
    }
});



Ext.Resizable.positions = {
    n: 'north', s: 'south', e: 'east', w: 'west', se: 'southeast', sw: 'southwest', nw: 'northwest', ne: 'northeast'
};

Ext.Resizable.Handle = Ext.extend(Object, {
    constructor : function(rz, pos, disableTrackOver, transparent, cls){
       if(!this.tpl){
            
            var tpl = Ext.DomHelper.createTemplate(
                {tag: 'div', cls: 'x-resizable-handle x-resizable-handle-{0}'}
            );
            tpl.compile();
            Ext.Resizable.Handle.prototype.tpl = tpl;
        }
        this.position = pos;
        this.rz = rz;
        this.el = this.tpl.append(rz.el.dom, [this.position], true);
        this.el.unselectable();
        if(transparent){
            this.el.setOpacity(0);
        }
        if(!Ext.isEmpty(cls)){
            this.el.addClass(cls);
        }
        this.el.on('mousedown', this.onMouseDown, this);
        if(!disableTrackOver){
            this.el.on({
                scope: this,
                mouseover: this.onMouseOver,
                mouseout: this.onMouseOut
            });
        }
    },

    
    afterResize : function(rz){
        
    },
    
    onMouseDown : function(e){
        this.rz.onMouseDown(this, e);
    },
    
    onMouseOver : function(e){
        this.rz.handleOver(this, e);
    },
    
    onMouseOut : function(e){
        this.rz.handleOut(this, e);
    },
    
    destroy : function(){
        Ext.destroy(this.el);
        this.el = null;
    }
});

Ext.Window = Ext.extend(Ext.Panel, {
    
    
    
    
    
    
    
    
    
    

    
    baseCls : 'x-window',
    
    resizable : true,
    
    draggable : true,
    
    closable : true,
    
    closeAction : 'close',
    
    constrain : false,
    
    constrainHeader : false,
    
    plain : false,
    
    minimizable : false,
    
    maximizable : false,
    
    minHeight : 100,
    
    minWidth : 200,
    
    expandOnShow : true,

    
    collapsible : false,

    
    initHidden : undefined,

    
    hidden : true,

    
    
    
    
    
    elements : 'header,body',
    
    frame : true,
    
    floating : true,

    
    initComponent : function(){
        this.initTools();
        Ext.Window.superclass.initComponent.call(this);
        this.addEvents(
            
            
            
            'resize',
            
            'maximize',
            
            'minimize',
            
            'restore'
        );
        
        if(Ext.isDefined(this.initHidden)){
            this.hidden = this.initHidden;
        }
        if(this.hidden === false){
            this.hidden = true;
            this.show();
        }
    },

    
    getState : function(){
        return Ext.apply(Ext.Window.superclass.getState.call(this) || {}, this.getBox(true));
    },

    
    onRender : function(ct, position){
        Ext.Window.superclass.onRender.call(this, ct, position);

        if(this.plain){
            this.el.addClass('x-window-plain');
        }

        
        this.focusEl = this.el.createChild({
                    tag: 'a', href:'#', cls:'x-dlg-focus',
                    tabIndex:'-1', html: '&#160;'});
        this.focusEl.swallowEvent('click', true);

        this.proxy = this.el.createProxy('x-window-proxy');
        this.proxy.enableDisplayMode('block');

        if(this.modal){
            this.mask = this.container.createChild({cls:'ext-el-mask'}, this.el.dom);
            this.mask.enableDisplayMode('block');
            this.mask.hide();
            this.mon(this.mask, 'click', this.focus, this);
        }
        if(this.maximizable){
            this.mon(this.header, 'dblclick', this.toggleMaximize, this);
        }
    },

    
    initEvents : function(){
        Ext.Window.superclass.initEvents.call(this);
        if(this.animateTarget){
            this.setAnimateTarget(this.animateTarget);
        }

        if(this.resizable){
            this.resizer = new Ext.Resizable(this.el, {
                minWidth: this.minWidth,
                minHeight:this.minHeight,
                handles: this.resizeHandles || 'all',
                pinned: true,
                resizeElement : this.resizerAction,
                handleCls: 'x-window-handle'
            });
            this.resizer.window = this;
            this.mon(this.resizer, 'beforeresize', this.beforeResize, this);
        }

        if(this.draggable){
            this.header.addClass('x-window-draggable');
        }
        this.mon(this.el, 'mousedown', this.toFront, this);
        this.manager = this.manager || Ext.WindowMgr;
        this.manager.register(this);
        if(this.maximized){
            this.maximized = false;
            this.maximize();
        }
        if(this.closable){
            var km = this.getKeyMap();
            km.on(27, this.onEsc, this);
            km.disable();
        }
    },

    initDraggable : function(){
        
        this.dd = new Ext.Window.DD(this);
    },

   
    onEsc : function(k, e){
        e.stopEvent();
        this[this.closeAction]();
    },

    
    beforeDestroy : function(){
        if(this.rendered){
            this.hide();
            this.clearAnchor();
            Ext.destroy(
                this.focusEl,
                this.resizer,
                this.dd,
                this.proxy,
                this.mask
            );
        }
        Ext.Window.superclass.beforeDestroy.call(this);
    },

    
    onDestroy : function(){
        if(this.manager){
            this.manager.unregister(this);
        }
        Ext.Window.superclass.onDestroy.call(this);
    },

    
    initTools : function(){
        if(this.minimizable){
            this.addTool({
                id: 'minimize',
                handler: this.minimize.createDelegate(this, [])
            });
        }
        if(this.maximizable){
            this.addTool({
                id: 'maximize',
                handler: this.maximize.createDelegate(this, [])
            });
            this.addTool({
                id: 'restore',
                handler: this.restore.createDelegate(this, []),
                hidden:true
            });
        }
        if(this.closable){
            this.addTool({
                id: 'close',
                handler: this[this.closeAction].createDelegate(this, [])
            });
        }
    },

    
    resizerAction : function(){
        var box = this.proxy.getBox();
        this.proxy.hide();
        this.window.handleResize(box);
        return box;
    },

    
    beforeResize : function(){
        this.resizer.minHeight = Math.max(this.minHeight, this.getFrameHeight() + 40); 
        this.resizer.minWidth = Math.max(this.minWidth, this.getFrameWidth() + 40);
        this.resizeBox = this.el.getBox();
    },

    
    updateHandles : function(){
        if(Ext.isIE && this.resizer){
            this.resizer.syncHandleHeight();
            this.el.repaint();
        }
    },

    
    handleResize : function(box){
        var rz = this.resizeBox;
        if(rz.x != box.x || rz.y != box.y){
            this.updateBox(box);
        }else{
            this.setSize(box);
            if (Ext.isIE6 && Ext.isStrict) {
                this.doLayout();
            }
        }
        this.focus();
        this.updateHandles();
        this.saveState();
    },

    
    focus : function(){
        var f = this.focusEl,
            db = this.defaultButton,
            t = typeof db,
            el,
            ct;
        if(Ext.isDefined(db)){
            if(Ext.isNumber(db) && this.fbar){
                f = this.fbar.items.get(db);
            }else if(Ext.isString(db)){
                f = Ext.getCmp(db);
            }else{
                f = db;
            }
            el = f.getEl();
            ct = Ext.getDom(this.container);
            if (el && ct) {
                if (!Ext.lib.Region.getRegion(ct).contains(Ext.lib.Region.getRegion(el.dom))){
                    return;
                }
            }
        }
        f = f || this.focusEl;
        f.focus.defer(10, f);
    },

    
    setAnimateTarget : function(el){
        el = Ext.get(el);
        this.animateTarget = el;
    },

    
    beforeShow : function(){
        delete this.el.lastXY;
        delete this.el.lastLT;
        if(this.x === undefined || this.y === undefined){
            var xy = this.el.getAlignToXY(this.container, 'c-c');
            var pos = this.el.translatePoints(xy[0], xy[1]);
            this.x = this.x === undefined? pos.left : this.x;
            this.y = this.y === undefined? pos.top : this.y;
        }
        this.el.setLeftTop(this.x, this.y);

        if(this.expandOnShow){
            this.expand(false);
        }

        if(this.modal){
            Ext.getBody().addClass('x-body-masked');
            this.mask.setSize(Ext.lib.Dom.getViewWidth(true), Ext.lib.Dom.getViewHeight(true));
            this.mask.show();
        }
    },

    
    show : function(animateTarget, cb, scope){
        if(!this.rendered){
            this.render(Ext.getBody());
        }
        if(this.hidden === false){
            this.toFront();
            return this;
        }
        if(this.fireEvent('beforeshow', this) === false){
            return this;
        }
        if(cb){
            this.on('show', cb, scope, {single:true});
        }
        this.hidden = false;
        if(Ext.isDefined(animateTarget)){
            this.setAnimateTarget(animateTarget);
        }
        this.beforeShow();
        if(this.animateTarget){
            this.animShow();
        }else{
            this.afterShow();
        }
        return this;
    },

    
    afterShow : function(isAnim){
        if (this.isDestroyed){
            return false;
        }
        this.proxy.hide();
        this.el.setStyle('display', 'block');
        this.el.show();
        if(this.maximized){
            this.fitContainer();
        }
        if(Ext.isMac && Ext.isGecko2){ 
            this.cascade(this.setAutoScroll);
        }

        if(this.monitorResize || this.modal || this.constrain || this.constrainHeader){
            Ext.EventManager.onWindowResize(this.onWindowResize, this);
        }
        this.doConstrain();
        this.doLayout();
        if(this.keyMap){
            this.keyMap.enable();
        }
        this.toFront();
        this.updateHandles();
        if(isAnim && (Ext.isIE || Ext.isWebKit)){
            var sz = this.getSize();
            this.onResize(sz.width, sz.height);
        }
        this.onShow();
        this.fireEvent('show', this);
    },

    
    animShow : function(){
        this.proxy.show();
        this.proxy.setBox(this.animateTarget.getBox());
        this.proxy.setOpacity(0);
        var b = this.getBox();
        this.el.setStyle('display', 'none');
        this.proxy.shift(Ext.apply(b, {
            callback: this.afterShow.createDelegate(this, [true], false),
            scope: this,
            easing: 'easeNone',
            duration: 0.25,
            opacity: 0.5
        }));
    },

    
    hide : function(animateTarget, cb, scope){
        if(this.hidden || this.fireEvent('beforehide', this) === false){
            return this;
        }
        if(cb){
            this.on('hide', cb, scope, {single:true});
        }
        this.hidden = true;
        if(animateTarget !== undefined){
            this.setAnimateTarget(animateTarget);
        }
        if(this.modal){
            this.mask.hide();
            Ext.getBody().removeClass('x-body-masked');
        }
        if(this.animateTarget){
            this.animHide();
        }else{
            this.el.hide();
            this.afterHide();
        }
        return this;
    },

    
    afterHide : function(){
        this.proxy.hide();
        if(this.monitorResize || this.modal || this.constrain || this.constrainHeader){
            Ext.EventManager.removeResizeListener(this.onWindowResize, this);
        }
        if(this.keyMap){
            this.keyMap.disable();
        }
        this.onHide();
        this.fireEvent('hide', this);
    },

    
    animHide : function(){
        this.proxy.setOpacity(0.5);
        this.proxy.show();
        var tb = this.getBox(false);
        this.proxy.setBox(tb);
        this.el.hide();
        this.proxy.shift(Ext.apply(this.animateTarget.getBox(), {
            callback: this.afterHide,
            scope: this,
            duration: 0.25,
            easing: 'easeNone',
            opacity: 0
        }));
    },

    
    onShow : Ext.emptyFn,

    
    onHide : Ext.emptyFn,

    
    onWindowResize : function(){
        if(this.maximized){
            this.fitContainer();
        }
        if(this.modal){
            this.mask.setSize('100%', '100%');
            var force = this.mask.dom.offsetHeight;
            this.mask.setSize(Ext.lib.Dom.getViewWidth(true), Ext.lib.Dom.getViewHeight(true));
        }
        this.doConstrain();
    },

    
    doConstrain : function(){
        if(this.constrain || this.constrainHeader){
            var offsets;
            if(this.constrain){
                offsets = {
                    right:this.el.shadowOffset,
                    left:this.el.shadowOffset,
                    bottom:this.el.shadowOffset
                };
            }else {
                var s = this.getSize();
                offsets = {
                    right:-(s.width - 100),
                    bottom:-(s.height - 25)
                };
            }

            var xy = this.el.getConstrainToXY(this.container, true, offsets);
            if(xy){
                this.setPosition(xy[0], xy[1]);
            }
        }
    },

    
    ghost : function(cls){
        var ghost = this.createGhost(cls);
        var box = this.getBox(true);
        ghost.setLeftTop(box.x, box.y);
        ghost.setWidth(box.width);
        this.el.hide();
        this.activeGhost = ghost;
        return ghost;
    },

    
    unghost : function(show, matchPosition){
        if(!this.activeGhost) {
            return;
        }
        if(show !== false){
            this.el.show();
            this.focus.defer(10, this);
            if(Ext.isMac && Ext.isGecko2){ 
                this.cascade(this.setAutoScroll);
            }
        }
        if(matchPosition !== false){
            this.setPosition(this.activeGhost.getLeft(true), this.activeGhost.getTop(true));
        }
        this.activeGhost.hide();
        this.activeGhost.remove();
        delete this.activeGhost;
    },

    
    minimize : function(){
        this.fireEvent('minimize', this);
        return this;
    },

    
    close : function(){
        if(this.fireEvent('beforeclose', this) !== false){
            if(this.hidden){
                this.doClose();
            }else{
                this.hide(null, this.doClose, this);
            }
        }
    },

    
    doClose : function(){
        this.fireEvent('close', this);
        this.destroy();
    },

    
    maximize : function(){
        if(!this.maximized){
            this.expand(false);
            this.restoreSize = this.getSize();
            this.restorePos = this.getPosition(true);
            if (this.maximizable){
                this.tools.maximize.hide();
                this.tools.restore.show();
            }
            this.maximized = true;
            this.el.disableShadow();

            if(this.dd){
                this.dd.lock();
            }
            if(this.collapsible){
                this.tools.toggle.hide();
            }
            this.el.addClass('x-window-maximized');
            this.container.addClass('x-window-maximized-ct');

            this.setPosition(0, 0);
            this.fitContainer();
            this.fireEvent('maximize', this);
        }
        return this;
    },

    
    restore : function(){
        if(this.maximized){
            var t = this.tools;
            this.el.removeClass('x-window-maximized');
            if(t.restore){
                t.restore.hide();
            }
            if(t.maximize){
                t.maximize.show();
            }
            this.setPosition(this.restorePos[0], this.restorePos[1]);
            this.setSize(this.restoreSize.width, this.restoreSize.height);
            delete this.restorePos;
            delete this.restoreSize;
            this.maximized = false;
            this.el.enableShadow(true);

            if(this.dd){
                this.dd.unlock();
            }
            if(this.collapsible && t.toggle){
                t.toggle.show();
            }
            this.container.removeClass('x-window-maximized-ct');

            this.doConstrain();
            this.fireEvent('restore', this);
        }
        return this;
    },

    
    toggleMaximize : function(){
        return this[this.maximized ? 'restore' : 'maximize']();
    },

    
    fitContainer : function(){
        var vs = this.container.getViewSize(false);
        this.setSize(vs.width, vs.height);
    },

    
    
    setZIndex : function(index){
        if(this.modal){
            this.mask.setStyle('z-index', index);
        }
        this.el.setZIndex(++index);
        index += 5;

        if(this.resizer){
            this.resizer.proxy.setStyle('z-index', ++index);
        }

        this.lastZIndex = index;
    },

    
    alignTo : function(element, position, offsets){
        var xy = this.el.getAlignToXY(element, position, offsets);
        this.setPagePosition(xy[0], xy[1]);
        return this;
    },

    
    anchorTo : function(el, alignment, offsets, monitorScroll){
        this.clearAnchor();
        this.anchorTarget = {
            el: el,
            alignment: alignment,
            offsets: offsets
        };

        Ext.EventManager.onWindowResize(this.doAnchor, this);
        var tm = typeof monitorScroll;
        if(tm != 'undefined'){
            Ext.EventManager.on(window, 'scroll', this.doAnchor, this,
                {buffer: tm == 'number' ? monitorScroll : 50});
        }
        return this.doAnchor();
    },

    
    doAnchor : function(){
        var o = this.anchorTarget;
        this.alignTo(o.el, o.alignment, o.offsets);
        return this;
    },

    
    clearAnchor : function(){
        if(this.anchorTarget){
            Ext.EventManager.removeResizeListener(this.doAnchor, this);
            Ext.EventManager.un(window, 'scroll', this.doAnchor, this);
            delete this.anchorTarget;
        }
        return this;
    },

    
    toFront : function(e){
        if(this.manager.bringToFront(this)){
            if(!e || !e.getTarget().focus){
                this.focus();
            }
        }
        return this;
    },

    
    setActive : function(active){
        if(active){
            if(!this.maximized){
                this.el.enableShadow(true);
            }
            this.fireEvent('activate', this);
        }else{
            this.el.disableShadow();
            this.fireEvent('deactivate', this);
        }
    },

    
    toBack : function(){
        this.manager.sendToBack(this);
        return this;
    },

    
    center : function(){
        var xy = this.el.getAlignToXY(this.container, 'c-c');
        this.setPagePosition(xy[0], xy[1]);
        return this;
    }

    
});
Ext.reg('window', Ext.Window);


Ext.Window.DD = function(win){
    this.win = win;
    Ext.Window.DD.superclass.constructor.call(this, win.el.id, 'WindowDD-'+win.id);
    this.setHandleElId(win.header.id);
    this.scroll = false;
};

Ext.extend(Ext.Window.DD, Ext.dd.DD, {
    moveOnly:true,
    headerOffsets:[100, 25],
    startDrag : function(){
        var w = this.win;
        this.proxy = w.ghost();
        if(w.constrain !== false){
            var so = w.el.shadowOffset;
            this.constrainTo(w.container, {right: so, left: so, bottom: so});
        }else if(w.constrainHeader !== false){
            var s = this.proxy.getSize();
            this.constrainTo(w.container, {right: -(s.width-this.headerOffsets[0]), bottom: -(s.height-this.headerOffsets[1])});
        }
    },
    b4Drag : Ext.emptyFn,

    onDrag : function(e){
        this.alignElWithMouse(this.proxy, e.getPageX(), e.getPageY());
    },

    endDrag : function(e){
        this.win.unghost();
        this.win.saveState();
    }
});

Ext.WindowGroup = function(){
    var list = {};
    var accessList = [];
    var front = null;

    
    var sortWindows = function(d1, d2){
        return (!d1._lastAccess || d1._lastAccess < d2._lastAccess) ? -1 : 1;
    };

    
    var orderWindows = function(){
        var a = accessList, len = a.length;
        if(len > 0){
            a.sort(sortWindows);
            var seed = a[0].manager.zseed;
            for(var i = 0; i < len; i++){
                var win = a[i];
                if(win && !win.hidden){
                    win.setZIndex(seed + (i*10));
                }
            }
        }
        activateLast();
    };

    
    var setActiveWin = function(win){
        if(win != front){
            if(front){
                front.setActive(false);
            }
            front = win;
            if(win){
                win.setActive(true);
            }
        }
    };

    
    var activateLast = function(){
        for(var i = accessList.length-1; i >=0; --i) {
            if(!accessList[i].hidden){
                setActiveWin(accessList[i]);
                return;
            }
        }
        
        setActiveWin(null);
    };

    return {
        
        zseed : 9000,

        
        register : function(win){
            if(win.manager){
                win.manager.unregister(win);
            }
            win.manager = this;

            list[win.id] = win;
            accessList.push(win);
            win.on('hide', activateLast);
        },

        
        unregister : function(win){
            delete win.manager;
            delete list[win.id];
            win.un('hide', activateLast);
            accessList.remove(win);
        },

        
        get : function(id){
            return typeof id == "object" ? id : list[id];
        },

        
        bringToFront : function(win){
            win = this.get(win);
            if(win != front){
                win._lastAccess = new Date().getTime();
                orderWindows();
                return true;
            }
            return false;
        },

        
        sendToBack : function(win){
            win = this.get(win);
            win._lastAccess = -(new Date().getTime());
            orderWindows();
            return win;
        },

        
        hideAll : function(){
            for(var id in list){
                if(list[id] && typeof list[id] != "function" && list[id].isVisible()){
                    list[id].hide();
                }
            }
        },

        
        getActive : function(){
            return front;
        },

        
        getBy : function(fn, scope){
            var r = [];
            for(var i = accessList.length-1; i >=0; --i) {
                var win = accessList[i];
                if(fn.call(scope||win, win) !== false){
                    r.push(win);
                }
            }
            return r;
        },

        
        each : function(fn, scope){
            for(var id in list){
                if(list[id] && typeof list[id] != "function"){
                    if(fn.call(scope || list[id], list[id]) === false){
                        return;
                    }
                }
            }
        }
    };
};



Ext.WindowMgr = new Ext.WindowGroup();
Ext.MessageBox = function(){
    var dlg, opt, mask, waitTimer,
        bodyEl, msgEl, textboxEl, textareaEl, progressBar, pp, iconEl, spacerEl,
        buttons, activeTextEl, bwidth, bufferIcon = '', iconCls = '',
        buttonNames = ['ok', 'yes', 'no', 'cancel'];

    
    var handleButton = function(button){
        buttons[button].blur();
        if(dlg.isVisible()){
            dlg.hide();
            handleHide();
            Ext.callback(opt.fn, opt.scope||window, [button, activeTextEl.dom.value, opt], 1);
        }
    };

    
    var handleHide = function(){
        if(opt && opt.cls){
            dlg.el.removeClass(opt.cls);
        }
        progressBar.reset();        
    };

    
    var handleEsc = function(d, k, e){
        if(opt && opt.closable !== false){
            dlg.hide();
            handleHide();
        }
        if(e){
            e.stopEvent();
        }
    };

    
    var updateButtons = function(b){
        var width = 0,
            cfg;
        if(!b){
            Ext.each(buttonNames, function(name){
                buttons[name].hide();
            });
            return width;
        }
        dlg.footer.dom.style.display = '';
        Ext.iterate(buttons, function(name, btn){
            cfg = b[name];
            if(cfg){
                btn.show();
                btn.setText(Ext.isString(cfg) ? cfg : Ext.MessageBox.buttonText[name]);
                width += btn.getEl().getWidth() + 15;
            }else{
                btn.hide();
            }
        });
        return width;
    };

    return {
        
        getDialog : function(titleText){
           if(!dlg){
                var btns = [];
                
                buttons = {};
                Ext.each(buttonNames, function(name){
                    btns.push(buttons[name] = new Ext.Button({
                        text: this.buttonText[name],
                        handler: handleButton.createCallback(name),
                        hideMode: 'offsets'
                    }));
                }, this);
                dlg = new Ext.Window({
                    autoCreate : true,
                    title:titleText,
                    resizable:false,
                    constrain:true,
                    constrainHeader:true,
                    minimizable : false,
                    maximizable : false,
                    stateful: false,
                    modal: true,
                    shim:true,
                    buttonAlign:"center",
                    width:400,
                    height:100,
                    minHeight: 80,
                    plain:true,
                    footer:true,
                    closable:true,
                    close : function(){
                        if(opt && opt.buttons && opt.buttons.no && !opt.buttons.cancel){
                            handleButton("no");
                        }else{
                            handleButton("cancel");
                        }
                    },
                    fbar: new Ext.Toolbar({
                        items: btns,
                        enableOverflow: false
                    })
                });
                dlg.render(document.body);
                dlg.getEl().addClass('x-window-dlg');
                mask = dlg.mask;
                bodyEl = dlg.body.createChild({
                    html:'<div class="ext-mb-icon"></div><div class="ext-mb-content"><span class="ext-mb-text"></span><br /><div class="ext-mb-fix-cursor"><input type="text" class="ext-mb-input" /><textarea class="ext-mb-textarea"></textarea></div></div>'
                });
                iconEl = Ext.get(bodyEl.dom.firstChild);
                var contentEl = bodyEl.dom.childNodes[1];
                msgEl = Ext.get(contentEl.firstChild);
                textboxEl = Ext.get(contentEl.childNodes[2].firstChild);
                textboxEl.enableDisplayMode();
                textboxEl.addKeyListener([10,13], function(){
                    if(dlg.isVisible() && opt && opt.buttons){
                        if(opt.buttons.ok){
                            handleButton("ok");
                        }else if(opt.buttons.yes){
                            handleButton("yes");
                        }
                    }
                });
                textareaEl = Ext.get(contentEl.childNodes[2].childNodes[1]);
                textareaEl.enableDisplayMode();
                progressBar = new Ext.ProgressBar({
                    renderTo:bodyEl
                });
               bodyEl.createChild({cls:'x-clear'});
            }
            return dlg;
        },

        
        updateText : function(text){
            if(!dlg.isVisible() && !opt.width){
                dlg.setSize(this.maxWidth, 100); 
            }
            msgEl.update(text || '&#160;');

            var iw = iconCls != '' ? (iconEl.getWidth() + iconEl.getMargins('lr')) : 0,
                mw = msgEl.getWidth() + msgEl.getMargins('lr'),
                fw = dlg.getFrameWidth('lr'),
                bw = dlg.body.getFrameWidth('lr'),
                w;
                
            if (Ext.isIE && iw > 0){
                
                
                iw += 3;
            }
            w = Math.max(Math.min(opt.width || iw+mw+fw+bw, opt.maxWidth || this.maxWidth),
                    Math.max(opt.minWidth || this.minWidth, bwidth || 0));

            if(opt.prompt === true){
                activeTextEl.setWidth(w-iw-fw-bw);
            }
            if(opt.progress === true || opt.wait === true){
                progressBar.setSize(w-iw-fw-bw);
            }
            if(Ext.isIE && w == bwidth){
                w += 4; 
            }
            dlg.setSize(w, 'auto').center();
            return this;
        },

        
        updateProgress : function(value, progressText, msg){
            progressBar.updateProgress(value, progressText);
            if(msg){
                this.updateText(msg);
            }
            return this;
        },

        
        isVisible : function(){
            return dlg && dlg.isVisible();
        },

        
        hide : function(){
            var proxy = dlg ? dlg.activeGhost : null;
            if(this.isVisible() || proxy){
                dlg.hide();
                handleHide();
                if (proxy){
                    
                    
                    dlg.unghost(false, false);
                } 
            }
            return this;
        },

        
        show : function(options){
            if(this.isVisible()){
                this.hide();
            }
            opt = options;
            var d = this.getDialog(opt.title || "&#160;");

            d.setTitle(opt.title || "&#160;");
            var allowClose = (opt.closable !== false && opt.progress !== true && opt.wait !== true);
            d.tools.close.setDisplayed(allowClose);
            activeTextEl = textboxEl;
            opt.prompt = opt.prompt || (opt.multiline ? true : false);
            if(opt.prompt){
                if(opt.multiline){
                    textboxEl.hide();
                    textareaEl.show();
                    textareaEl.setHeight(Ext.isNumber(opt.multiline) ? opt.multiline : this.defaultTextHeight);
                    activeTextEl = textareaEl;
                }else{
                    textboxEl.show();
                    textareaEl.hide();
                }
            }else{
                textboxEl.hide();
                textareaEl.hide();
            }
            activeTextEl.dom.value = opt.value || "";
            if(opt.prompt){
                d.focusEl = activeTextEl;
            }else{
                var bs = opt.buttons;
                var db = null;
                if(bs && bs.ok){
                    db = buttons["ok"];
                }else if(bs && bs.yes){
                    db = buttons["yes"];
                }
                if (db){
                    d.focusEl = db;
                }
            }
            if(opt.iconCls){
              d.setIconClass(opt.iconCls);
            }
            this.setIcon(Ext.isDefined(opt.icon) ? opt.icon : bufferIcon);
            bwidth = updateButtons(opt.buttons);
            progressBar.setVisible(opt.progress === true || opt.wait === true);
            this.updateProgress(0, opt.progressText);
            this.updateText(opt.msg);
            if(opt.cls){
                d.el.addClass(opt.cls);
            }
            d.proxyDrag = opt.proxyDrag === true;
            d.modal = opt.modal !== false;
            d.mask = opt.modal !== false ? mask : false;
            if(!d.isVisible()){
                
                document.body.appendChild(dlg.el.dom);
                d.setAnimateTarget(opt.animEl);
                
                d.on('show', function(){
                    if(allowClose === true){
                        d.keyMap.enable();
                    }else{
                        d.keyMap.disable();
                    }
                }, this, {single:true});
                d.show(opt.animEl);
            }
            if(opt.wait === true){
                progressBar.wait(opt.waitConfig);
            }
            return this;
        },

        
        setIcon : function(icon){
            if(!dlg){
                bufferIcon = icon;
                return;
            }
            bufferIcon = undefined;
            if(icon && icon != ''){
                iconEl.removeClass('x-hidden');
                iconEl.replaceClass(iconCls, icon);
                bodyEl.addClass('x-dlg-icon');
                iconCls = icon;
            }else{
                iconEl.replaceClass(iconCls, 'x-hidden');
                bodyEl.removeClass('x-dlg-icon');
                iconCls = '';
            }
            return this;
        },

        
        progress : function(title, msg, progressText){
            this.show({
                title : title,
                msg : msg,
                buttons: false,
                progress:true,
                closable:false,
                minWidth: this.minProgressWidth,
                progressText: progressText
            });
            return this;
        },

        
        wait : function(msg, title, config){
            this.show({
                title : title,
                msg : msg,
                buttons: false,
                closable:false,
                wait:true,
                modal:true,
                minWidth: this.minProgressWidth,
                waitConfig: config
            });
            return this;
        },

        
        alert : function(title, msg, fn, scope){
            this.show({
                title : title,
                msg : msg,
                buttons: this.OK,
                fn: fn,
                scope : scope,
                minWidth: this.minWidth
            });
            return this;
        },

        
        confirm : function(title, msg, fn, scope){
            this.show({
                title : title,
                msg : msg,
                buttons: this.YESNO,
                fn: fn,
                scope : scope,
                icon: this.QUESTION,
                minWidth: this.minWidth
            });
            return this;
        },

        
        prompt : function(title, msg, fn, scope, multiline, value){
            this.show({
                title : title,
                msg : msg,
                buttons: this.OKCANCEL,
                fn: fn,
                minWidth: this.minPromptWidth,
                scope : scope,
                prompt:true,
                multiline: multiline,
                value: value
            });
            return this;
        },

        
        OK : {ok:true},
        
        CANCEL : {cancel:true},
        
        OKCANCEL : {ok:true, cancel:true},
        
        YESNO : {yes:true, no:true},
        
        YESNOCANCEL : {yes:true, no:true, cancel:true},
        
        INFO : 'ext-mb-info',
        
        WARNING : 'ext-mb-warning',
        
        QUESTION : 'ext-mb-question',
        
        ERROR : 'ext-mb-error',

        
        defaultTextHeight : 75,
        
        maxWidth : 600,
        
        minWidth : 100,
        
        minProgressWidth : 250,
        
        minPromptWidth: 250,
        
        buttonText : {
            ok : "OK",
            cancel : "Cancel",
            yes : "Yes",
            no : "No"
        }
    };
}();


Ext.Msg = Ext.MessageBox;
Ext.dd.PanelProxy = function(panel, config){
    this.panel = panel;
    this.id = this.panel.id +'-ddproxy';
    Ext.apply(this, config);
};

Ext.dd.PanelProxy.prototype = {
    
    insertProxy : true,

    
    setStatus : Ext.emptyFn,
    reset : Ext.emptyFn,
    update : Ext.emptyFn,
    stop : Ext.emptyFn,
    sync: Ext.emptyFn,

    
    getEl : function(){
        return this.ghost;
    },

    
    getGhost : function(){
        return this.ghost;
    },

    
    getProxy : function(){
        return this.proxy;
    },

    
    hide : function(){
        if(this.ghost){
            if(this.proxy){
                this.proxy.remove();
                delete this.proxy;
            }
            this.panel.el.dom.style.display = '';
            this.ghost.remove();
            delete this.ghost;
        }
    },

    
    show : function(){
        if(!this.ghost){
            this.ghost = this.panel.createGhost(undefined, undefined, Ext.getBody());
            this.ghost.setXY(this.panel.el.getXY());
            if(this.insertProxy){
                this.proxy = this.panel.el.insertSibling({cls:'x-panel-dd-spacer'});
                this.proxy.setSize(this.panel.getSize());
            }
            this.panel.el.dom.style.display = 'none';
        }
    },

    
    repair : function(xy, callback, scope){
        this.hide();
        if(typeof callback == "function"){
            callback.call(scope || this);
        }
    },

    
    moveProxy : function(parentNode, before){
        if(this.proxy){
            parentNode.insertBefore(this.proxy.dom, before);
        }
    }
};


Ext.Panel.DD = function(panel, cfg){
    this.panel = panel;
    this.dragData = {panel: panel};
    this.proxy = new Ext.dd.PanelProxy(panel, cfg);
    Ext.Panel.DD.superclass.constructor.call(this, panel.el, cfg);
    var h = panel.header;
    if(h){
        this.setHandleElId(h.id);
    }
    (h ? h : this.panel.body).setStyle('cursor', 'move');
    this.scroll = false;
};

Ext.extend(Ext.Panel.DD, Ext.dd.DragSource, {
    showFrame: Ext.emptyFn,
    startDrag: Ext.emptyFn,
    b4StartDrag: function(x, y) {
        this.proxy.show();
    },
    b4MouseDown: function(e) {
        var x = e.getPageX();
        var y = e.getPageY();
        this.autoOffset(x, y);
    },
    onInitDrag : function(x, y){
        this.onStartDrag(x, y);
        return true;
    },
    createFrame : Ext.emptyFn,
    getDragEl : function(e){
        return this.proxy.ghost.dom;
    },
    endDrag : function(e){
        this.proxy.hide();
        this.panel.saveState();
    },

    autoOffset : function(x, y) {
        x -= this.startPageX;
        y -= this.startPageY;
        this.setDelta(x, y);
    }
});
Ext.state.Provider = function(){
    
    this.addEvents("statechange");
    this.state = {};
    Ext.state.Provider.superclass.constructor.call(this);
};
Ext.extend(Ext.state.Provider, Ext.util.Observable, {
    
    get : function(name, defaultValue){
        return typeof this.state[name] == "undefined" ?
            defaultValue : this.state[name];
    },

    
    clear : function(name){
        delete this.state[name];
        this.fireEvent("statechange", this, name, null);
    },

    
    set : function(name, value){
        this.state[name] = value;
        this.fireEvent("statechange", this, name, value);
    },

    
    decodeValue : function(cookie){
        var re = /^(a|n|d|b|s|o)\:(.*)$/;
        var matches = re.exec(unescape(cookie));
        if(!matches || !matches[1]) return; 
        var type = matches[1];
        var v = matches[2];
        switch(type){
            case "n":
                return parseFloat(v);
            case "d":
                return new Date(Date.parse(v));
            case "b":
                return (v == "1");
            case "a":
                var all = [];
                if(v != ''){
                    Ext.each(v.split('^'), function(val){
                        all.push(this.decodeValue(val));
                    }, this);
                }
                return all;
           case "o":
                var all = {};
                if(v != ''){
                    Ext.each(v.split('^'), function(val){
                        var kv = val.split('=');
                        all[kv[0]] = this.decodeValue(kv[1]);
                    }, this);
                }
                return all;
           default:
                return v;
        }
    },

    
    encodeValue : function(v){
        var enc;
        if(typeof v == "number"){
            enc = "n:" + v;
        }else if(typeof v == "boolean"){
            enc = "b:" + (v ? "1" : "0");
        }else if(Ext.isDate(v)){
            enc = "d:" + v.toGMTString();
        }else if(Ext.isArray(v)){
            var flat = "";
            for(var i = 0, len = v.length; i < len; i++){
                flat += this.encodeValue(v[i]);
                if(i != len-1) flat += "^";
            }
            enc = "a:" + flat;
        }else if(typeof v == "object"){
            var flat = "";
            for(var key in v){
                if(typeof v[key] != "function" && v[key] !== undefined){
                    flat += key + "=" + this.encodeValue(v[key]) + "^";
                }
            }
            enc = "o:" + flat.substring(0, flat.length-1);
        }else{
            enc = "s:" + v;
        }
        return escape(enc);
    }
});

Ext.state.Manager = function(){
    var provider = new Ext.state.Provider();

    return {
        
        setProvider : function(stateProvider){
            provider = stateProvider;
        },

        
        get : function(key, defaultValue){
            return provider.get(key, defaultValue);
        },

        
         set : function(key, value){
            provider.set(key, value);
        },

        
        clear : function(key){
            provider.clear(key);
        },

        
        getProvider : function(){
            return provider;
        }
    };
}();

Ext.state.CookieProvider = function(config){
    Ext.state.CookieProvider.superclass.constructor.call(this);
    this.path = "/";
    this.expires = new Date(new Date().getTime()+(1000*60*60*24*7)); 
    this.domain = null;
    this.secure = false;
    Ext.apply(this, config);
    this.state = this.readCookies();
};

Ext.extend(Ext.state.CookieProvider, Ext.state.Provider, {
    
    set : function(name, value){
        if(typeof value == "undefined" || value === null){
            this.clear(name);
            return;
        }
        this.setCookie(name, value);
        Ext.state.CookieProvider.superclass.set.call(this, name, value);
    },

    
    clear : function(name){
        this.clearCookie(name);
        Ext.state.CookieProvider.superclass.clear.call(this, name);
    },

    
    readCookies : function(){
        var cookies = {};
        var c = document.cookie + ";";
        var re = /\s?(.*?)=(.*?);/g;
    	var matches;
    	while((matches = re.exec(c)) != null){
            var name = matches[1];
            var value = matches[2];
            if(name && name.substring(0,3) == "ys-"){
                cookies[name.substr(3)] = this.decodeValue(value);
            }
        }
        return cookies;
    },

    
    setCookie : function(name, value){
        document.cookie = "ys-"+ name + "=" + this.encodeValue(value) +
           ((this.expires == null) ? "" : ("; expires=" + this.expires.toGMTString())) +
           ((this.path == null) ? "" : ("; path=" + this.path)) +
           ((this.domain == null) ? "" : ("; domain=" + this.domain)) +
           ((this.secure == true) ? "; secure" : "");
    },

    
    clearCookie : function(name){
        document.cookie = "ys-" + name + "=null; expires=Thu, 01-Jan-70 00:00:01 GMT" +
           ((this.path == null) ? "" : ("; path=" + this.path)) +
           ((this.domain == null) ? "" : ("; domain=" + this.domain)) +
           ((this.secure == true) ? "; secure" : "");
    }
});
Ext.DataView = Ext.extend(Ext.BoxComponent, {
    
    
    
    
    
    
    
    
    
    selectedClass : "x-view-selected",
    
    emptyText : "",

    
    deferEmptyText: true,
    
    trackOver: false,
    
    
    blockRefresh: false,

    
    last: false,

    
    initComponent : function(){
        Ext.DataView.superclass.initComponent.call(this);
        if(Ext.isString(this.tpl) || Ext.isArray(this.tpl)){
            this.tpl = new Ext.XTemplate(this.tpl);
        }

        this.addEvents(
            
            "beforeclick",
            
            "click",
            
            "mouseenter",
            
            "mouseleave",
            
            "containerclick",
            
            "dblclick",
            
            "contextmenu",
            
            "containercontextmenu",
            
            "selectionchange",

            
            "beforeselect"
        );

        this.store = Ext.StoreMgr.lookup(this.store);
        this.all = new Ext.CompositeElementLite();
        this.selected = new Ext.CompositeElementLite();
    },

    
    afterRender : function(){
        Ext.DataView.superclass.afterRender.call(this);

		this.mon(this.getTemplateTarget(), {
            "click": this.onClick,
            "dblclick": this.onDblClick,
            "contextmenu": this.onContextMenu,
            scope:this
        });

        if(this.overClass || this.trackOver){
            this.mon(this.getTemplateTarget(), {
                "mouseover": this.onMouseOver,
                "mouseout": this.onMouseOut,
                scope:this
            });
        }

        if(this.store){
            this.bindStore(this.store, true);
        }
    },

    
    refresh : function() {
        this.clearSelections(false, true);
        var el = this.getTemplateTarget();
        el.update("");
        var records = this.store.getRange();
        if(records.length < 1){
            if(!this.deferEmptyText || this.hasSkippedEmptyText){
                el.update(this.emptyText);
            }
            this.all.clear();
        }else{
            this.tpl.overwrite(el, this.collectData(records, 0));
            this.all.fill(Ext.query(this.itemSelector, el.dom));
            this.updateIndexes(0);
        }
        this.hasSkippedEmptyText = true;
    },

    getTemplateTarget: function(){
        return this.el;
    },

    
    prepareData : function(data){
        return data;
    },

    
    collectData : function(records, startIndex){
        var r = [];
        for(var i = 0, len = records.length; i < len; i++){
            r[r.length] = this.prepareData(records[i].data, startIndex+i, records[i]);
        }
        return r;
    },

    
    bufferRender : function(records){
        var div = document.createElement('div');
        this.tpl.overwrite(div, this.collectData(records));
        return Ext.query(this.itemSelector, div);
    },

    
    onUpdate : function(ds, record){
        var index = this.store.indexOf(record);
        if(index > -1){
            var sel = this.isSelected(index);
            var original = this.all.elements[index];
            var node = this.bufferRender([record], index)[0];

            this.all.replaceElement(index, node, true);
            if(sel){
                this.selected.replaceElement(original, node);
                this.all.item(index).addClass(this.selectedClass);
            }
            this.updateIndexes(index, index);
        }
    },

    
    onAdd : function(ds, records, index){
        if(this.all.getCount() === 0){
            this.refresh();
            return;
        }
        var nodes = this.bufferRender(records, index), n, a = this.all.elements;
        if(index < this.all.getCount()){
            n = this.all.item(index).insertSibling(nodes, 'before', true);
            a.splice.apply(a, [index, 0].concat(nodes));
        }else{
            n = this.all.last().insertSibling(nodes, 'after', true);
            a.push.apply(a, nodes);
        }
        this.updateIndexes(index);
    },

    
    onRemove : function(ds, record, index){
        this.deselect(index);
        this.all.removeElement(index, true);
        this.updateIndexes(index);
        if (this.store.getCount() === 0){
            this.refresh();
        }
    },

    
    refreshNode : function(index){
        this.onUpdate(this.store, this.store.getAt(index));
    },

    
    updateIndexes : function(startIndex, endIndex){
        var ns = this.all.elements;
        startIndex = startIndex || 0;
        endIndex = endIndex || ((endIndex === 0) ? 0 : (ns.length - 1));
        for(var i = startIndex; i <= endIndex; i++){
            ns[i].viewIndex = i;
        }
    },
    
    
    getStore : function(){
        return this.store;
    },

    
    bindStore : function(store, initial){
        if(!initial && this.store){
            if(store !== this.store && this.store.autoDestroy){
                this.store.destroy();
            }else{
                this.store.un("beforeload", this.onBeforeLoad, this);
                this.store.un("datachanged", this.onDataChanged, this);
                this.store.un("add", this.onAdd, this);
                this.store.un("remove", this.onRemove, this);
                this.store.un("update", this.onUpdate, this);
                this.store.un("clear", this.refresh, this);
            }
            if(!store){
                this.store = null;
            }
        }
        if(store){
            store = Ext.StoreMgr.lookup(store);
            store.on({
                scope: this,
                beforeload: this.onBeforeLoad,
                datachanged: this.onDataChanged,
                add: this.onAdd,
                remove: this.onRemove,
                update: this.onUpdate,
                clear: this.refresh
            });
        }
        this.store = store;
        if(store){
            this.refresh();
        }
    },
    
    
    onDataChanged: function() {
        if (this.blockRefresh !== true) {
            this.refresh.apply(this, arguments);
        }
    },

    
    findItemFromChild : function(node){
        return Ext.fly(node).findParent(this.itemSelector, this.getTemplateTarget());
    },

    
    onClick : function(e){
        var item = e.getTarget(this.itemSelector, this.getTemplateTarget());
        if(item){
            var index = this.indexOf(item);
            if(this.onItemClick(item, index, e) !== false){
                this.fireEvent("click", this, index, item, e);
            }
        }else{
            if(this.fireEvent("containerclick", this, e) !== false){
                this.onContainerClick(e);
            }
        }
    },

    onContainerClick : function(e){
        this.clearSelections();
    },

    
    onContextMenu : function(e){
        var item = e.getTarget(this.itemSelector, this.getTemplateTarget());
        if(item){
            this.fireEvent("contextmenu", this, this.indexOf(item), item, e);
        }else{
            this.fireEvent("containercontextmenu", this, e);
        }
    },

    
    onDblClick : function(e){
        var item = e.getTarget(this.itemSelector, this.getTemplateTarget());
        if(item){
            this.fireEvent("dblclick", this, this.indexOf(item), item, e);
        }
    },

    
    onMouseOver : function(e){
        var item = e.getTarget(this.itemSelector, this.getTemplateTarget());
        if(item && item !== this.lastItem){
            this.lastItem = item;
            Ext.fly(item).addClass(this.overClass);
            this.fireEvent("mouseenter", this, this.indexOf(item), item, e);
        }
    },

    
    onMouseOut : function(e){
        if(this.lastItem){
            if(!e.within(this.lastItem, true, true)){
                Ext.fly(this.lastItem).removeClass(this.overClass);
                this.fireEvent("mouseleave", this, this.indexOf(this.lastItem), this.lastItem, e);
                delete this.lastItem;
            }
        }
    },

    
    onItemClick : function(item, index, e){
        if(this.fireEvent("beforeclick", this, index, item, e) === false){
            return false;
        }
        if(this.multiSelect){
            this.doMultiSelection(item, index, e);
            e.preventDefault();
        }else if(this.singleSelect){
            this.doSingleSelection(item, index, e);
            e.preventDefault();
        }
        return true;
    },

    
    doSingleSelection : function(item, index, e){
        if(e.ctrlKey && this.isSelected(index)){
            this.deselect(index);
        }else{
            this.select(index, false);
        }
    },

    
    doMultiSelection : function(item, index, e){
        if(e.shiftKey && this.last !== false){
            var last = this.last;
            this.selectRange(last, index, e.ctrlKey);
            this.last = last; 
        }else{
            if((e.ctrlKey||this.simpleSelect) && this.isSelected(index)){
                this.deselect(index);
            }else{
                this.select(index, e.ctrlKey || e.shiftKey || this.simpleSelect);
            }
        }
    },

    
    getSelectionCount : function(){
        return this.selected.getCount();
    },

    
    getSelectedNodes : function(){
        return this.selected.elements;
    },

    
    getSelectedIndexes : function(){
        var indexes = [], s = this.selected.elements;
        for(var i = 0, len = s.length; i < len; i++){
            indexes.push(s[i].viewIndex);
        }
        return indexes;
    },

    
    getSelectedRecords : function(){
        var r = [], s = this.selected.elements;
        for(var i = 0, len = s.length; i < len; i++){
            r[r.length] = this.store.getAt(s[i].viewIndex);
        }
        return r;
    },

    
    getRecords : function(nodes){
        var r = [], s = nodes;
        for(var i = 0, len = s.length; i < len; i++){
            r[r.length] = this.store.getAt(s[i].viewIndex);
        }
        return r;
    },

    
    getRecord : function(node){
        return this.store.getAt(node.viewIndex);
    },

    
    clearSelections : function(suppressEvent, skipUpdate){
        if((this.multiSelect || this.singleSelect) && this.selected.getCount() > 0){
            if(!skipUpdate){
                this.selected.removeClass(this.selectedClass);
            }
            this.selected.clear();
            this.last = false;
            if(!suppressEvent){
                this.fireEvent("selectionchange", this, this.selected.elements);
            }
        }
    },

    
    isSelected : function(node){
        return this.selected.contains(this.getNode(node));
    },

    
    deselect : function(node){
        if(this.isSelected(node)){
            node = this.getNode(node);
            this.selected.removeElement(node);
            if(this.last == node.viewIndex){
                this.last = false;
            }
            Ext.fly(node).removeClass(this.selectedClass);
            this.fireEvent("selectionchange", this, this.selected.elements);
        }
    },

    
    select : function(nodeInfo, keepExisting, suppressEvent){
        if(Ext.isArray(nodeInfo)){
            if(!keepExisting){
                this.clearSelections(true);
            }
            for(var i = 0, len = nodeInfo.length; i < len; i++){
                this.select(nodeInfo[i], true, true);
            }
            if(!suppressEvent){
                this.fireEvent("selectionchange", this, this.selected.elements);
            }
        } else{
            var node = this.getNode(nodeInfo);
            if(!keepExisting){
                this.clearSelections(true);
            }
            if(node && !this.isSelected(node)){
                if(this.fireEvent("beforeselect", this, node, this.selected.elements) !== false){
                    Ext.fly(node).addClass(this.selectedClass);
                    this.selected.add(node);
                    this.last = node.viewIndex;
                    if(!suppressEvent){
                        this.fireEvent("selectionchange", this, this.selected.elements);
                    }
                }
            }
        }
    },

    
    selectRange : function(start, end, keepExisting){
        if(!keepExisting){
            this.clearSelections(true);
        }
        this.select(this.getNodes(start, end), true);
    },

    
    getNode : function(nodeInfo){
        if(Ext.isString(nodeInfo)){
            return document.getElementById(nodeInfo);
        }else if(Ext.isNumber(nodeInfo)){
            return this.all.elements[nodeInfo];
        }else if(nodeInfo instanceof Ext.data.Record){
            var idx = this.store.indexOf(nodeInfo);
            return this.all.elements[idx];
        }
        return nodeInfo;
    },

    
    getNodes : function(start, end){
        var ns = this.all.elements;
        start = start || 0;
        end = !Ext.isDefined(end) ? Math.max(ns.length - 1, 0) : end;
        var nodes = [], i;
        if(start <= end){
            for(i = start; i <= end && ns[i]; i++){
                nodes.push(ns[i]);
            }
        } else{
            for(i = start; i >= end && ns[i]; i--){
                nodes.push(ns[i]);
            }
        }
        return nodes;
    },

    
    indexOf : function(node){
        node = this.getNode(node);
        if(Ext.isNumber(node.viewIndex)){
            return node.viewIndex;
        }
        return this.all.indexOf(node);
    },

    
    onBeforeLoad : function(){
        if(this.loadingText){
            this.clearSelections(false, true);
            this.getTemplateTarget().update('<div class="loading-indicator">'+this.loadingText+'</div>');
            this.all.clear();
        }
    },

    onDestroy : function(){
        this.all.clear();
        this.selected.clear();
        Ext.DataView.superclass.onDestroy.call(this);
        this.bindStore(null);
    }
});


Ext.DataView.prototype.setStore = Ext.DataView.prototype.bindStore;

Ext.reg('dataview', Ext.DataView);

Ext.list.ListView = Ext.extend(Ext.DataView, {
    
    
    
    itemSelector: 'dl',
    
    selectedClass:'x-list-selected',
    
    overClass:'x-list-over',
    
    
    scrollOffset : undefined,
    
    columnResize: true,
    
    
    columnSort: true,
    

    
    maxWidth: Ext.isIE ? 99 : 100,

    initComponent : function(){
        if(this.columnResize){
            this.colResizer = new Ext.list.ColumnResizer(this.colResizer);
            this.colResizer.init(this);
        }
        if(this.columnSort){
            this.colSorter = new Ext.list.Sorter(this.columnSort);
            this.colSorter.init(this);
        }
        if(!this.internalTpl){
            this.internalTpl = new Ext.XTemplate(
                '<div class="x-list-header"><div class="x-list-header-inner">',
                    '<tpl for="columns">',
                    '<div style="width:{[values.width*100]}%;text-align:{align};"><em unselectable="on" id="',this.id, '-xlhd-{#}">',
                        '{header}',
                    '</em></div>',
                    '</tpl>',
                    '<div class="x-clear"></div>',
                '</div></div>',
                '<div class="x-list-body"><div class="x-list-body-inner">',
                '</div></div>'
            );
        }
        if(!this.tpl){
            this.tpl = new Ext.XTemplate(
                '<tpl for="rows">',
                    '<dl>',
                        '<tpl for="parent.columns">',
                        '<dt style="width:{[values.width*100]}%;text-align:{align};">',
                        '<em unselectable="on"<tpl if="cls"> class="{cls}</tpl>">',
                            '{[values.tpl.apply(parent)]}',
                        '</em></dt>',
                        '</tpl>',
                        '<div class="x-clear"></div>',
                    '</dl>',
                '</tpl>'
            );
        };

        var cs = this.columns,
            allocatedWidth = 0,
            colsWithWidth = 0,
            len = cs.length,
            columns = [];

        for(var i = 0; i < len; i++){
            var c = cs[i];
            if(!c.isColumn) {
                c.xtype = c.xtype ? (/^lv/.test(c.xtype) ? c.xtype : 'lv' + c.xtype) : 'lvcolumn';
                c = Ext.create(c);
            }
            if(c.width) {
                allocatedWidth += c.width*100;
                colsWithWidth++;
            }
            columns.push(c);
        }

        cs = this.columns = columns;

        
        if(colsWithWidth < len){
            var remaining = len - colsWithWidth;
            if(allocatedWidth < this.maxWidth){
                var perCol = ((this.maxWidth-allocatedWidth) / remaining)/100;
                for(var j = 0; j < len; j++){
                    var c = cs[j];
                    if(!c.width){
                        c.width = perCol;
                    }
                }
            }
        }
        Ext.list.ListView.superclass.initComponent.call(this);
    },

    onRender : function(){
        this.autoEl = {
            cls: 'x-list-wrap'
        };
        Ext.list.ListView.superclass.onRender.apply(this, arguments);

        this.internalTpl.overwrite(this.el, {columns: this.columns});

        this.innerBody = Ext.get(this.el.dom.childNodes[1].firstChild);
        this.innerHd = Ext.get(this.el.dom.firstChild.firstChild);

        if(this.hideHeaders){
            this.el.dom.firstChild.style.display = 'none';
        }
    },

    getTemplateTarget : function(){
        return this.innerBody;
    },

    
    collectData : function(){
        var rs = Ext.list.ListView.superclass.collectData.apply(this, arguments);
        return {
            columns: this.columns,
            rows: rs
        }
    },

    verifyInternalSize : function(){
        if(this.lastSize){
            this.onResize(this.lastSize.width, this.lastSize.height);
        }
    },

    
    onResize : function(w, h){
        var bd = this.innerBody.dom;
        var hd = this.innerHd.dom;
        if(!bd){
            return;
        }
        var bdp = bd.parentNode;
        if(Ext.isNumber(w)){
            var sw = w - Ext.num(this.scrollOffset, Ext.getScrollBarWidth());
            if(this.reserveScrollOffset || ((bdp.offsetWidth - bdp.clientWidth) > 10)){
                bd.style.width = sw + 'px';
                hd.style.width = sw + 'px';
            }else{
                bd.style.width = w + 'px';
                hd.style.width = w + 'px';
                setTimeout(function(){
                    if((bdp.offsetWidth - bdp.clientWidth) > 10){
                        bd.style.width = sw + 'px';
                        hd.style.width = sw + 'px';
                    }
                }, 10);
            }
        }
        if(Ext.isNumber(h)){
            bdp.style.height = (h - hd.parentNode.offsetHeight) + 'px';
        }
    },

    updateIndexes : function(){
        Ext.list.ListView.superclass.updateIndexes.apply(this, arguments);
        this.verifyInternalSize();
    },

    findHeaderIndex : function(hd){
        hd = hd.dom || hd;
        var pn = hd.parentNode, cs = pn.parentNode.childNodes;
        for(var i = 0, c; c = cs[i]; i++){
            if(c == pn){
                return i;
            }
        }
        return -1;
    },

    setHdWidths : function(){
        var els = this.innerHd.dom.getElementsByTagName('div');
        for(var i = 0, cs = this.columns, len = cs.length; i < len; i++){
            els[i].style.width = (cs[i].width*100) + '%';
        }
    }
});

Ext.reg('listview', Ext.list.ListView);


Ext.ListView = Ext.list.ListView;
Ext.list.Column = Ext.extend(Object, {
    
    isColumn: true,
    
            
    align: 'left',
        
    header: '',
    
        
    width: null,

    
    cls: '',
    
    

    
    
    constructor : function(c){
        if(!c.tpl){
            c.tpl = new Ext.XTemplate('{' + c.dataIndex + '}');
        }
        else if(Ext.isString(c.tpl)){
            c.tpl = new Ext.XTemplate(c.tpl);
        }
        
        Ext.apply(this, c);
    }
});

Ext.reg('lvcolumn', Ext.list.Column);


Ext.list.NumberColumn = Ext.extend(Ext.list.Column, {
        
    format: '0,000.00',
    
    constructor : function(c) {
        c.tpl = c.tpl || new Ext.XTemplate('{' + c.dataIndex + ':number("' + (c.format || this.format) + '")}');       
        Ext.list.NumberColumn.superclass.constructor.call(this, c);
    }
});

Ext.reg('lvnumbercolumn', Ext.list.NumberColumn);


Ext.list.DateColumn = Ext.extend(Ext.list.Column, {
    format: 'm/d/Y',
    constructor : function(c) {
        c.tpl = c.tpl || new Ext.XTemplate('{' + c.dataIndex + ':date("' + (c.format || this.format) + '")}');      
        Ext.list.DateColumn.superclass.constructor.call(this, c);
    }
});
Ext.reg('lvdatecolumn', Ext.list.DateColumn);


Ext.list.BooleanColumn = Ext.extend(Ext.list.Column, {
    
    trueText: 'true',
    
    falseText: 'false',
    
    undefinedText: '&#160;',
    
    constructor : function(c) {
        c.tpl = c.tpl || new Ext.XTemplate('{' + c.dataIndex + ':this.format}');
        
        var t = this.trueText, f = this.falseText, u = this.undefinedText;
        c.tpl.format = function(v){
            if(v === undefined){
                return u;
            }
            if(!v || v === 'false'){
                return f;
            }
            return t;
        };
        
        Ext.list.DateColumn.superclass.constructor.call(this, c);
    }
});

Ext.reg('lvbooleancolumn', Ext.list.BooleanColumn);
Ext.list.ColumnResizer = Ext.extend(Ext.util.Observable, {
    
    minPct: .05,

    constructor: function(config){
        Ext.apply(this, config);
        Ext.list.ColumnResizer.superclass.constructor.call(this);
    },
    init : function(listView){
        this.view = listView;
        listView.on('render', this.initEvents, this);
    },

    initEvents : function(view){
        view.mon(view.innerHd, 'mousemove', this.handleHdMove, this);
        this.tracker = new Ext.dd.DragTracker({
            onBeforeStart: this.onBeforeStart.createDelegate(this),
            onStart: this.onStart.createDelegate(this),
            onDrag: this.onDrag.createDelegate(this),
            onEnd: this.onEnd.createDelegate(this),
            tolerance: 3,
            autoStart: 300
        });
        this.tracker.initEl(view.innerHd);
        view.on('beforedestroy', this.tracker.destroy, this.tracker);
    },

    handleHdMove : function(e, t){
        var hw = 5,
            x = e.getPageX(),
            hd = e.getTarget('em', 3, true);
        if(hd){
            var r = hd.getRegion(),
                ss = hd.dom.style,
                pn = hd.dom.parentNode;

            if(x - r.left <= hw && pn != pn.parentNode.firstChild){
                this.activeHd = Ext.get(pn.previousSibling.firstChild);
                ss.cursor = Ext.isWebKit ? 'e-resize' : 'col-resize';
            } else if(r.right - x <= hw && pn != pn.parentNode.lastChild.previousSibling){
                this.activeHd = hd;
                ss.cursor = Ext.isWebKit ? 'w-resize' : 'col-resize';
            } else{
                delete this.activeHd;
                ss.cursor = '';
            }
        }
    },

    onBeforeStart : function(e){
        this.dragHd = this.activeHd;
        return !!this.dragHd;
    },

    onStart: function(e){
        this.view.disableHeaders = true;
        this.proxy = this.view.el.createChild({cls:'x-list-resizer'});
        this.proxy.setHeight(this.view.el.getHeight());

        var x = this.tracker.getXY()[0],
            w = this.view.innerHd.getWidth();

        this.hdX = this.dragHd.getX();
        this.hdIndex = this.view.findHeaderIndex(this.dragHd);

        this.proxy.setX(this.hdX);
        this.proxy.setWidth(x-this.hdX);

        this.minWidth = w*this.minPct;
        this.maxWidth = w - (this.minWidth*(this.view.columns.length-1-this.hdIndex));
    },

    onDrag: function(e){
        var cursorX = this.tracker.getXY()[0];
        this.proxy.setWidth((cursorX-this.hdX).constrain(this.minWidth, this.maxWidth));
    },

    onEnd: function(e){
        
        var nw = this.proxy.getWidth();
        this.proxy.remove();

        var index = this.hdIndex,
            vw = this.view,
            cs = vw.columns,
            len = cs.length,
            w = this.view.innerHd.getWidth(),
            minPct = this.minPct * 100,
            pct = Math.ceil((nw * vw.maxWidth) / w),
            diff = (cs[index].width * 100) - pct,
            eachItem = Math.floor(diff / (len-1-index)),
            mod = diff - (eachItem * (len-1-index));

        for(var i = index+1; i < len; i++){
            var cw = (cs[i].width * 100) + eachItem,
                ncw = Math.max(minPct, cw);
            if(cw != ncw){
                mod += cw - ncw;
            }
            cs[i].width = ncw / 100;
        }
        cs[index].width = pct / 100;
        cs[index+1].width += (mod / 100);
        delete this.dragHd;
        vw.setHdWidths();
        vw.refresh();
        setTimeout(function(){
            vw.disableHeaders = false;
        }, 100);
    }
});


Ext.ListView.ColumnResizer = Ext.list.ColumnResizer;
Ext.list.Sorter = Ext.extend(Ext.util.Observable, {
    
    sortClasses : ["sort-asc", "sort-desc"],

    constructor: function(config){
        Ext.apply(this, config);
        Ext.list.Sorter.superclass.constructor.call(this);
    },

    init : function(listView){
        this.view = listView;
        listView.on('render', this.initEvents, this);
    },

    initEvents : function(view){
        view.mon(view.innerHd, 'click', this.onHdClick, this);
        view.innerHd.setStyle('cursor', 'pointer');
        view.mon(view.store, 'datachanged', this.updateSortState, this);
        this.updateSortState.defer(10, this, [view.store]);
    },

    updateSortState : function(store){
        var state = store.getSortState();
        if(!state){
            return;
        }
        this.sortState = state;
        var cs = this.view.columns, sortColumn = -1;
        for(var i = 0, len = cs.length; i < len; i++){
            if(cs[i].dataIndex == state.field){
                sortColumn = i;
                break;
            }
        }
        if(sortColumn != -1){
            var sortDir = state.direction;
            this.updateSortIcon(sortColumn, sortDir);
        }
    },

    updateSortIcon : function(col, dir){
        var sc = this.sortClasses;
        var hds = this.view.innerHd.select('em').removeClass(sc);
        hds.item(col).addClass(sc[dir == "DESC" ? 1 : 0]);
    },

    onHdClick : function(e){
        var hd = e.getTarget('em', 3);
        if(hd && !this.view.disableHeaders){
            var index = this.view.findHeaderIndex(hd);
            this.view.store.sort(this.view.columns[index].dataIndex);
        }
    }
});


Ext.ListView.Sorter = Ext.list.Sorter;
Ext.TabPanel = Ext.extend(Ext.Panel,  {
    
    
    
    deferredRender : true,
    
    tabWidth : 120,
    
    minTabWidth : 30,
    
    resizeTabs : false,
    
    enableTabScroll : false,
    
    scrollIncrement : 0,
    
    scrollRepeatInterval : 400,
    
    scrollDuration : 0.35,
    
    animScroll : true,
    
    tabPosition : 'top',
    
    baseCls : 'x-tab-panel',
    
    autoTabs : false,
    
    autoTabSelector : 'div.x-tab',
    
    activeTab : undefined,
    
    tabMargin : 2,
    
    plain : false,
    
    wheelIncrement : 20,

    
    idDelimiter : '__',

    
    itemCls : 'x-tab-item',

    
    elements : 'body',
    headerAsText : false,
    frame : false,
    hideBorders :true,

    
    initComponent : function(){
        this.frame = false;
        Ext.TabPanel.superclass.initComponent.call(this);
        this.addEvents(
            
            'beforetabchange',
            
            'tabchange',
            
            'contextmenu'
        );
        
        this.setLayout(new Ext.layout.CardLayout(Ext.apply({
            layoutOnCardChange: this.layoutOnTabChange,
            deferredRender: this.deferredRender
        }, this.layoutConfig)));

        if(this.tabPosition == 'top'){
            this.elements += ',header';
            this.stripTarget = 'header';
        }else {
            this.elements += ',footer';
            this.stripTarget = 'footer';
        }
        if(!this.stack){
            this.stack = Ext.TabPanel.AccessStack();
        }
        this.initItems();
    },

    
    onRender : function(ct, position){
        Ext.TabPanel.superclass.onRender.call(this, ct, position);

        if(this.plain){
            var pos = this.tabPosition == 'top' ? 'header' : 'footer';
            this[pos].addClass('x-tab-panel-'+pos+'-plain');
        }

        var st = this[this.stripTarget];

        this.stripWrap = st.createChild({cls:'x-tab-strip-wrap', cn:{
            tag:'ul', cls:'x-tab-strip x-tab-strip-'+this.tabPosition}});

        var beforeEl = (this.tabPosition=='bottom' ? this.stripWrap : null);
        st.createChild({cls:'x-tab-strip-spacer'}, beforeEl);
        this.strip = new Ext.Element(this.stripWrap.dom.firstChild);

        
        this.edge = this.strip.createChild({tag:'li', cls:'x-tab-edge', cn: [{tag: 'span', cls: 'x-tab-strip-text', cn: '&#160;'}]});
        this.strip.createChild({cls:'x-clear'});

        this.body.addClass('x-tab-panel-body-'+this.tabPosition);

        
        if(!this.itemTpl){
            var tt = new Ext.Template(
                 '<li class="{cls}" id="{id}"><a class="x-tab-strip-close"></a>',
                 '<a class="x-tab-right" href="#"><em class="x-tab-left">',
                 '<span class="x-tab-strip-inner"><span class="x-tab-strip-text {iconCls}">{text}</span></span>',
                 '</em></a></li>'
            );
            tt.disableFormats = true;
            tt.compile();
            Ext.TabPanel.prototype.itemTpl = tt;
        }

        this.items.each(this.initTab, this);
    },

    
    afterRender : function(){
        Ext.TabPanel.superclass.afterRender.call(this);
        if(this.autoTabs){
            this.readTabs(false);
        }
        if(this.activeTab !== undefined){
            var item = Ext.isObject(this.activeTab) ? this.activeTab : this.items.get(this.activeTab);
            delete this.activeTab;
            this.setActiveTab(item);
        }
    },

    
    initEvents : function(){
        Ext.TabPanel.superclass.initEvents.call(this);
        this.mon(this.strip, {
            scope: this,
            mousedown: this.onStripMouseDown,
            contextmenu: this.onStripContextMenu
        });
        if(this.enableTabScroll){
            this.mon(this.strip, 'mousewheel', this.onWheel, this);
        }
    },

    
    findTargets : function(e){
        var item = null,
            itemEl = e.getTarget('li:not(.x-tab-edge)', this.strip);

        if(itemEl){
            item = this.getComponent(itemEl.id.split(this.idDelimiter)[1]);
            if(item.disabled){
                return {
                    close : null,
                    item : null,
                    el : null
                };
            }
        }
        return {
            close : e.getTarget('.x-tab-strip-close', this.strip),
            item : item,
            el : itemEl
        };
    },

    
    onStripMouseDown : function(e){
        if(e.button !== 0){
            return;
        }
        e.preventDefault();
        var t = this.findTargets(e);
        if(t.close){
            if (t.item.fireEvent('beforeclose', t.item) !== false) {
                t.item.fireEvent('close', t.item);
                this.remove(t.item);
            }
            return;
        }
        if(t.item && t.item != this.activeTab){
            this.setActiveTab(t.item);
        }
    },

    
    onStripContextMenu : function(e){
        e.preventDefault();
        var t = this.findTargets(e);
        if(t.item){
            this.fireEvent('contextmenu', this, t.item, e);
        }
    },

    
    readTabs : function(removeExisting){
        if(removeExisting === true){
            this.items.each(function(item){
                this.remove(item);
            }, this);
        }
        var tabs = this.el.query(this.autoTabSelector);
        for(var i = 0, len = tabs.length; i < len; i++){
            var tab = tabs[i],
                title = tab.getAttribute('title');
            tab.removeAttribute('title');
            this.add({
                title: title,
                contentEl: tab
            });
        }
    },

    
    initTab : function(item, index){
        var before = this.strip.dom.childNodes[index],
            p = this.getTemplateArgs(item),
            el = before ?
                 this.itemTpl.insertBefore(before, p) :
                 this.itemTpl.append(this.strip, p),
            cls = 'x-tab-strip-over',
            tabEl = Ext.get(el);

        tabEl.hover(function(){
            if(!item.disabled){
                tabEl.addClass(cls);
            }
        }, function(){
            tabEl.removeClass(cls);
        });

        if(item.tabTip){
            tabEl.child('span.x-tab-strip-text', true).qtip = item.tabTip;
        }
        item.tabEl = el;

        
        tabEl.select('a').on('click', function(e){
            if(!e.getPageX()){
                this.onStripMouseDown(e);
            }
        }, this, {preventDefault: true});

        item.on({
            scope: this,
            disable: this.onItemDisabled,
            enable: this.onItemEnabled,
            titlechange: this.onItemTitleChanged,
            iconchange: this.onItemIconChanged,
            beforeshow: this.onBeforeShowItem
        });
    },



    
    getTemplateArgs : function(item) {
        var cls = item.closable ? 'x-tab-strip-closable' : '';
        if(item.disabled){
            cls += ' x-item-disabled';
        }
        if(item.iconCls){
            cls += ' x-tab-with-icon';
        }
        if(item.tabCls){
            cls += ' ' + item.tabCls;
        }

        return {
            id: this.id + this.idDelimiter + item.getItemId(),
            text: item.title,
            cls: cls,
            iconCls: item.iconCls || ''
        };
    },

    
    onAdd : function(c){
        Ext.TabPanel.superclass.onAdd.call(this, c);
        if(this.rendered){
            var items = this.items;
            this.initTab(c, items.indexOf(c));
            this.delegateUpdates();
        }
    },

    
    onBeforeAdd : function(item){
        var existing = item.events ? (this.items.containsKey(item.getItemId()) ? item : null) : this.items.get(item);
        if(existing){
            this.setActiveTab(item);
            return false;
        }
        Ext.TabPanel.superclass.onBeforeAdd.apply(this, arguments);
        var es = item.elements;
        item.elements = es ? es.replace(',header', '') : es;
        item.border = (item.border === true);
    },

    
    onRemove : function(c){
        var te = Ext.get(c.tabEl);
        
        if(te){
            te.select('a').removeAllListeners();
            Ext.destroy(te);
        }
        Ext.TabPanel.superclass.onRemove.call(this, c);
        this.stack.remove(c);
        delete c.tabEl;
        c.un('disable', this.onItemDisabled, this);
        c.un('enable', this.onItemEnabled, this);
        c.un('titlechange', this.onItemTitleChanged, this);
        c.un('iconchange', this.onItemIconChanged, this);
        c.un('beforeshow', this.onBeforeShowItem, this);
        if(c == this.activeTab){
            var next = this.stack.next();
            if(next){
                this.setActiveTab(next);
            }else if(this.items.getCount() > 0){
                this.setActiveTab(0);
            }else{
                this.setActiveTab(null);
            }
        }
        if(!this.destroying){
            this.delegateUpdates();
        }
    },

    
    onBeforeShowItem : function(item){
        if(item != this.activeTab){
            this.setActiveTab(item);
            return false;
        }
    },

    
    onItemDisabled : function(item){
        var el = this.getTabEl(item);
        if(el){
            Ext.fly(el).addClass('x-item-disabled');
        }
        this.stack.remove(item);
    },

    
    onItemEnabled : function(item){
        var el = this.getTabEl(item);
        if(el){
            Ext.fly(el).removeClass('x-item-disabled');
        }
    },

    
    onItemTitleChanged : function(item){
        var el = this.getTabEl(item);
        if(el){
            Ext.fly(el).child('span.x-tab-strip-text', true).innerHTML = item.title;
        }
    },

    
    onItemIconChanged : function(item, iconCls, oldCls){
        var el = this.getTabEl(item);
        if(el){
            el = Ext.get(el);
            el.child('span.x-tab-strip-text').replaceClass(oldCls, iconCls);
            el[Ext.isEmpty(iconCls) ? 'removeClass' : 'addClass']('x-tab-with-icon');
        }
    },

    
    getTabEl : function(item){
        var c = this.getComponent(item);
        return c ? c.tabEl : null;
    },

    
    onResize : function(){
        Ext.TabPanel.superclass.onResize.apply(this, arguments);
        this.delegateUpdates();
    },

    
    beginUpdate : function(){
        this.suspendUpdates = true;
    },

    
    endUpdate : function(){
        this.suspendUpdates = false;
        this.delegateUpdates();
    },

    
    hideTabStripItem : function(item){
        item = this.getComponent(item);
        var el = this.getTabEl(item);
        if(el){
            el.style.display = 'none';
            this.delegateUpdates();
        }
        this.stack.remove(item);
    },

    
    unhideTabStripItem : function(item){
        item = this.getComponent(item);
        var el = this.getTabEl(item);
        if(el){
            el.style.display = '';
            this.delegateUpdates();
        }
    },

    
    delegateUpdates : function(){
        if(this.suspendUpdates){
            return;
        }
        if(this.resizeTabs && this.rendered){
            this.autoSizeTabs();
        }
        if(this.enableTabScroll && this.rendered){
            this.autoScrollTabs();
        }
    },

    
    autoSizeTabs : function(){
        var count = this.items.length,
            ce = this.tabPosition != 'bottom' ? 'header' : 'footer',
            ow = this[ce].dom.offsetWidth,
            aw = this[ce].dom.clientWidth;

        if(!this.resizeT