/**
 * vuex v3.1.2
 * (c) 2019 Evan You
 * @license MIT
 */
const t=("undefined"!=typeof window?window:"undefined"!=typeof global?global:{}).__VUE_DEVTOOLS_GLOBAL_HOOK__;function e(t,e){Object.keys(t).forEach(s=>e(t[s],s))}function s(t){return null!==t&&"object"==typeof t}class i{constructor(t,e){this.runtime=e,this._children=Object.create(null),this._rawModule=t;const s=t.state;this.state=("function"==typeof s?s():s)||{}}get namespaced(){return!!this._rawModule.namespaced}addChild(t,e){this._children[t]=e}removeChild(t){delete this._children[t]}getChild(t){return this._children[t]}update(t){this._rawModule.namespaced=t.namespaced,t.actions&&(this._rawModule.actions=t.actions),t.mutations&&(this._rawModule.mutations=t.mutations),t.getters&&(this._rawModule.getters=t.getters)}forEachChild(t){e(this._children,t)}forEachGetter(t){this._rawModule.getters&&e(this._rawModule.getters,t)}forEachAction(t){this._rawModule.actions&&e(this._rawModule.actions,t)}forEachMutation(t){this._rawModule.mutations&&e(this._rawModule.mutations,t)}}class o{constructor(t){this.register([],t,!1)}get(t){return t.reduce((t,e)=>t.getChild(e),this.root)}getNamespace(t){let e=this.root;return t.reduce((t,s)=>t+((e=e.getChild(s)).namespaced?s+"/":""),"")}update(t){!function t(e,s,i){s.update(i);if(i.modules)for(const o in i.modules){if(!s.getChild(o))return;t(e.concat(o),s.getChild(o),i.modules[o])}}([],this.root,t)}register(t,s,o=!0){const n=new i(s,o);if(0===t.length)this.root=n;else{this.get(t.slice(0,-1)).addChild(t[t.length-1],n)}s.modules&&e(s.modules,(e,s)=>{this.register(t.concat(s),e,o)})}unregister(t){const e=this.get(t.slice(0,-1)),s=t[t.length-1];e.getChild(s).runtime&&e.removeChild(s)}}let n;class r{constructor(e={}){!n&&"undefined"!=typeof window&&window.Vue&&p(window.Vue);const{plugins:s=[],strict:i=!1}=e;this._committing=!1,this._actions=Object.create(null),this._actionSubscribers=[],this._mutations=Object.create(null),this._wrappedGetters=Object.create(null),this._modules=new o(e),this._modulesNamespaceMap=Object.create(null),this._subscribers=[],this._watcherVM=new n,this._makeLocalGettersCache=Object.create(null);const r=this,{dispatch:c,commit:a}=this;this.dispatch=function(t,e){return c.call(r,t,e)},this.commit=function(t,e,s){return a.call(r,t,e,s)},this.strict=i;const l=this._modules.root.state;h(this,l,[],this._modules.root),u(this,l),s.forEach(t=>t(this)),(void 0!==e.devtools?e.devtools:n.config.devtools)&&function(e){t&&(e._devtoolHook=t,t.emit("vuex:init",e),t.on("vuex:travel-to-state",t=>{e.replaceState(t)}),e.subscribe((e,s)=>{t.emit("vuex:mutation",e,s)}))}(this)}get state(){return this._vm._data.$$state}set state(t){}commit(t,e,s){const{type:i,payload:o,options:n}=d(t,e,s),r={type:i,payload:o},c=this._mutations[i];c&&(this._withCommit(()=>{c.forEach(function(t){t(o)})}),this._subscribers.forEach(t=>t(r,this.state)))}dispatch(t,e){const{type:s,payload:i}=d(t,e),o={type:s,payload:i},n=this._actions[s];if(n){try{this._actionSubscribers.filter(t=>t.before).forEach(t=>t.before(o,this.state))}catch(t){}return(n.length>1?Promise.all(n.map(t=>t(i))):n[0](i)).then(t=>{try{this._actionSubscribers.filter(t=>t.after).forEach(t=>t.after(o,this.state))}catch(t){}return t})}}subscribe(t){return c(t,this._subscribers)}subscribeAction(t){return c("function"==typeof t?{before:t}:t,this._actionSubscribers)}watch(t,e,s){return this._watcherVM.$watch(()=>t(this.state,this.getters),e,s)}replaceState(t){this._withCommit(()=>{this._vm._data.$$state=t})}registerModule(t,e,s={}){"string"==typeof t&&(t=[t]),this._modules.register(t,e),h(this,this.state,t,this._modules.get(t),s.preserveState),u(this,this.state)}unregisterModule(t){"string"==typeof t&&(t=[t]),this._modules.unregister(t),this._withCommit(()=>{const e=l(this.state,t.slice(0,-1));n.delete(e,t[t.length-1])}),a(this)}hotUpdate(t){this._modules.update(t),a(this,!0)}_withCommit(t){const e=this._committing;this._committing=!0,t(),this._committing=e}}function c(t,e){return e.indexOf(t)<0&&e.push(t),()=>{const s=e.indexOf(t);s>-1&&e.splice(s,1)}}function a(t,e){t._actions=Object.create(null),t._mutations=Object.create(null),t._wrappedGetters=Object.create(null),t._modulesNamespaceMap=Object.create(null);const s=t.state;h(t,s,[],t._modules.root,!0),u(t,s,e)}function u(t,s,i){const o=t._vm;t.getters={},t._makeLocalGettersCache=Object.create(null);const r=t._wrappedGetters,c={};e(r,(e,s)=>{c[s]=function(t,e){return function(){return t(e)}}(e,t),Object.defineProperty(t.getters,s,{get:()=>t._vm[s],enumerable:!0})});const a=n.config.silent;n.config.silent=!0,t._vm=new n({data:{$$state:s},computed:c}),n.config.silent=a,t.strict&&function(t){t._vm.$watch(function(){return this._data.$$state},()=>{},{deep:!0,sync:!0})}(t),o&&(i&&t._withCommit(()=>{o._data.$$state=null}),n.nextTick(()=>o.$destroy()))}function h(t,e,s,i,o){const r=!s.length,c=t._modules.getNamespace(s);if(i.namespaced&&(t._modulesNamespaceMap[c],t._modulesNamespaceMap[c]=i),!r&&!o){const o=l(e,s.slice(0,-1)),r=s[s.length-1];t._withCommit(()=>{n.set(o,r,i.state)})}const a=i.context=function(t,e,s){const i=""===e,o={dispatch:i?t.dispatch:(s,i,o)=>{const n=d(s,i,o),{payload:r,options:c}=n;let{type:a}=n;return c&&c.root||(a=e+a),t.dispatch(a,r)},commit:i?t.commit:(s,i,o)=>{const n=d(s,i,o),{payload:r,options:c}=n;let{type:a}=n;c&&c.root||(a=e+a),t.commit(a,r,c)}};return Object.defineProperties(o,{getters:{get:i?()=>t.getters:()=>(function(t,e){if(!t._makeLocalGettersCache[e]){const s={},i=e.length;Object.keys(t.getters).forEach(o=>{if(o.slice(0,i)!==e)return;const n=o.slice(i);Object.defineProperty(s,n,{get:()=>t.getters[o],enumerable:!0})}),t._makeLocalGettersCache[e]=s}return t._makeLocalGettersCache[e]})(t,e)},state:{get:()=>l(t.state,s)}}),o}(t,c,s);i.forEachMutation((e,s)=>{!function(t,e,s,i){(t._mutations[e]||(t._mutations[e]=[])).push(function(e){s.call(t,i.state,e)})}(t,c+s,e,a)}),i.forEachAction((e,s)=>{const i=e.root?s:c+s,o=e.handler||e;!function(t,e,s,i){(t._actions[e]||(t._actions[e]=[])).push(function(e){let o=s.call(t,{dispatch:i.dispatch,commit:i.commit,getters:i.getters,state:i.state,rootGetters:t.getters,rootState:t.state},e);var n;return(n=o)&&"function"==typeof n.then||(o=Promise.resolve(o)),t._devtoolHook?o.catch(e=>{throw t._devtoolHook.emit("vuex:error",e),e}):o})}(t,i,o,a)}),i.forEachGetter((e,s)=>{!function(t,e,s,i){if(t._wrappedGetters[e])return;t._wrappedGetters[e]=function(t){return s(i.state,i.getters,t.state,t.getters)}}(t,c+s,e,a)}),i.forEachChild((i,n)=>{h(t,e,s.concat(n),i,o)})}function l(t,e){return e.length?e.reduce((t,e)=>t[e],t):t}function d(t,e,i){return s(t)&&t.type&&(i=e,e=t,t=t.type),{type:t,payload:e,options:i}}function p(t){n&&t===n||function(t){if(Number(t.version.split(".")[0])>=2)t.mixin({beforeCreate:e});else{const s=t.prototype._init;t.prototype._init=function(t={}){t.init=t.init?[e].concat(t.init):e,s.call(this,t)}}function e(){const t=this.$options;t.store?this.$store="function"==typeof t.store?t.store():t.store:t.parent&&t.parent.$store&&(this.$store=t.parent.$store)}}(n=t)}const m=w((t,e)=>{const s={};return b(e).forEach(({key:e,val:i})=>{s[e]=function(){let e=this.$store.state,s=this.$store.getters;if(t){const i=v(this.$store,"mapState",t);if(!i)return;e=i.context.state,s=i.context.getters}return"function"==typeof i?i.call(this,e,s):e[i]},s[e].vuex=!0}),s}),f=w((t,e)=>{const s={};return b(e).forEach(({key:e,val:i})=>{s[e]=function(...e){let s=this.$store.commit;if(t){const e=v(this.$store,"mapMutations",t);if(!e)return;s=e.context.commit}return"function"==typeof i?i.apply(this,[s].concat(e)):s.apply(this.$store,[i].concat(e))}}),s}),_=w((t,e)=>{const s={};return b(e).forEach(({key:e,val:i})=>{i=t+i,s[e]=function(){if(!t||v(this.$store,"mapGetters",t))return this.$store.getters[i]},s[e].vuex=!0}),s}),g=w((t,e)=>{const s={};return b(e).forEach(({key:e,val:i})=>{s[e]=function(...e){let s=this.$store.dispatch;if(t){const e=v(this.$store,"mapActions",t);if(!e)return;s=e.context.dispatch}return"function"==typeof i?i.apply(this,[s].concat(e)):s.apply(this.$store,[i].concat(e))}}),s}),y=t=>({mapState:m.bind(null,t),mapGetters:_.bind(null,t),mapMutations:f.bind(null,t),mapActions:g.bind(null,t)});function b(t){return function(t){return Array.isArray(t)||s(t)}(t)?Array.isArray(t)?t.map(t=>({key:t,val:t})):Object.keys(t).map(e=>({key:e,val:t[e]})):[]}function w(t){return(e,s)=>("string"!=typeof e?(s=e,e=""):"/"!==e.charAt(e.length-1)&&(e+="/"),t(e,s))}function v(t,e,s){return t._modulesNamespaceMap[s]}export default{Store:r,install:p,version:"3.1.2",mapState:m,mapMutations:f,mapGetters:_,mapActions:g,createNamespacedHelpers:y};export{r as Store,p as install,m as mapState,f as mapMutations,_ as mapGetters,g as mapActions,y as createNamespacedHelpers};