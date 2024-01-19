import{_ as b,r as h,o,c,a as e,w as x,b as D,v as L,n as V,d as l,t as m,i as _,e as f,h as g,F as C,j as I}from"./app-8617d8f9.js";const E={inject:["authStore"],name:"Permission",scrollToTop:!1,data(){return{form:new this.$form({searchInput:""}),permissions:[],currentPage:1,totalPages:1,currentPageData:[],roleId:0,roleDetails:[],permissionsUpdate:!1,permissionsDelete:!1,permissionsAdd:!1,permissionsView:!1,loading:!0,view:5}},computed:{visiblePaginationLinks(){const s=Math.floor(2.5);let a=Math.max(1,this.currentPage-s),d=Math.min(this.totalPages,a+5-1);this.totalPages>=5&&d===this.totalPages&&(a=Math.max(1,d-5+1));const t=[];for(let r=a;r<=d;r++)t.push(r);return t}},async mounted(){if(this.$useHead({title:"Permission",description:"Permission page"}),this.authStore.user===null)this.loading=!1;else{let i=!1;try{const s=["permissionsView","permissionsAdd","permissionsDelete","permissionsUpdate"],a=await checkPermissions(s);s.forEach(d=>{a[d]&&(this[d]=!0,d==="permissionsView"&&(i=!0))})}catch(s){handleError(s,this.$toast)}await this.fetchPermissionList(i)}},methods:{async changePage(i){try{const s=await this.$axios.get(`/api/permission?page=${i}&view=${this.view}&search=${this.form.searchInput}`);s.status===200&&(this.currentPageData=s.data.data.data,this.currentPage=i)}catch(s){handleError(s,this.$toast)}},async fetchPermissionList(i){if(i){try{await this.$axios.get(`/api/permission?view=${this.view}`).then(s=>{this.currentPageData=s.data.data.data,this.totalPages=s.data.data.last_page})}catch(s){handleError(s,this.$toast)}this.loading=!1}else this.loading=!1},async deletePermission(i){if(await this.showConfirmationDialog("Are you sure you want to delete this Permission?"))try{(await this.$axios.delete(`/api/permission/${i}`)).data.status===200&&(this.$toast.success("Permission deleted successfully.",{position:"top-right",duration:3e3}),await this.fetchPermissionList(!0))}catch(a){handleError(a,this.$toast)}},SearchPermission(){try{this.$axios.get("/api/permission",{params:{search:this.form.searchInput,view:this.view}}).then(i=>{i.status===200&&(this.currentPageData=i.data.data.data,this.totalPages=i.data.data.last_page)})}catch(i){handleError(i,this.$toast)}}}},S={key:0},U={key:0},M=e("h1",null,"Permissions",-1),N={class:"container my-4"},j={class:"row justify-content-center"},A={class:"col-md-8"},B={class:"input-group mb-3"},T={class:"input-group-append"},z=["disabled"],F={class:"text-end my-3"},O={key:0},R={class:"table table-bordered"},$=e("thead",{class:"text-white grey-bg"},[e("tr",null,[e("th",{class:""}," ID "),e("th",{class:"col-1"}," Name "),e("th",{class:"col-2"}," Description "),e("th",{class:"col-2"}," Category "),e("th",{class:"col-8 text-center"}," Action ")])],-1),K={class:"text-center"},H=["onClick"],q={key:1,class:"d-flex justify-content-center align-items-center"},G=e("h3",null,"NO RECORD FOUND",-1),J=[G],Q={key:1,class:"d-flex justify-content-center align-items-center"},W={key:1};function X(i,s,a,d,t,r){const P=h("has-error"),u=h("font-awesome-icon"),p=h("router-link"),y=h("pagination"),w=h("Unauthorized"),k=h("Loader");return t.loading===!1?(o(),c("div",S,[t.permissionsView===!0?(o(),c("div",U,[M,e("div",N,[e("div",j,[e("div",A,[e("form",{onSubmit:s[1]||(s[1]=x((...n)=>r.SearchPermission&&r.SearchPermission(...n),["prevent"])),onKeydown:s[2]||(s[2]=n=>t.form.onKeydown(n))},[e("div",B,[D(e("input",{"onUpdate:modelValue":s[0]||(s[0]=n=>t.form.searchInput=n),class:V([{"is-invalid":t.form.errors.has("searchInput")},"form-control"]),placeholder:"Search records...",type:"text",name:"searchInput"},null,2),[[L,t.form.searchInput]]),l(P,{form:t.form,field:"searchInput"},null,8,["form"]),e("div",T,[e("button",{class:"btn grey-bg ms-2",disabled:t.form.busy},m("Search"),8,z)])])],32)])])]),e("div",F,[t.permissionsAdd?(o(),_(p,{key:0,to:{name:"permission.add"},class:"btn linked-icon"},{default:f(()=>[l(u,{icon:"plus","fixed-width":""})]),_:1},8,["to"])):g("",!0)]),t.currentPageData&&t.currentPageData.length>0?(o(),c("div",O,[e("table",R,[$,e("tbody",null,[(o(!0),c(C,null,I(t.currentPageData,(n,v)=>(o(),c("tr",{key:v},[e("td",null,m(n.id),1),e("td",null,m(n.name),1),e("td",null,m(n.description),1),e("td",null,m(n.category),1),e("td",K,[l(p,{to:{path:`/viewpermission/${n.id}`},class:"btn linked-icon"},{default:f(()=>[l(u,{icon:"eye","fixed-width":""})]),_:2},1032,["to"]),t.permissionsUpdate?(o(),_(p,{key:0,to:{path:`/updatepermission/${n.id}`},class:"btn linked-icon"},{default:f(()=>[l(u,{icon:"pen","fixed-width":""})]),_:2},1032,["to"])):g("",!0),t.permissionsDelete?(o(),c("button",{key:1,class:"btn linked-icon",onClick:Y=>r.deletePermission(n.id)},[l(u,{icon:"trash","fixed-width":""})],8,H)):g("",!0)])]))),128))])])])):(o(),c("div",q,J)),e("div",null,[l(y,{currentPage:t.currentPage,totalPages:t.totalPages,visiblePaginationLinks:r.visiblePaginationLinks,changePage:r.changePage},null,8,["currentPage","totalPages","visiblePaginationLinks","changePage"])])])):(o(),c("div",Q,[l(w)]))])):(o(),c("div",W,[l(k)]))}const ee=b(E,[["render",X]]);export{ee as default};