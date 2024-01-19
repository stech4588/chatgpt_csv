import{_ as f,r as u,o as l,c as i,a as e,t as n,F as k,j as b,h as d,d as h,g as y}from"./app-8617d8f9.js";const D={name:"View",inject:["authStore"],data(){return{roleDetails:[],rolePermissions:[],roleUpdate:!1,roleDelete:!1,roleView:!1,loading:!0}},async mounted(){if(this.authStore.user===null)this.loading=!1;else{const r=["roleView","roleDelete","roleUpdate"],o=await checkPermissions(r);r.forEach(t=>{o[t]&&(this[t]=!0,t==="roleView"&&(this.loading=!1))});try{await this.$axios.get(`/api/role/${this.$route.params.id}`).then(t=>{this.roleDetails=t.data.data})}catch(t){handleError(t,this.$toast)}}},methods:{async deleteRole(r){if(await this.showConfirmationDialog("Are you sure you want to delete this Role?"))try{await this.$axios.delete(`/api/role/${r}`).then(t=>{t.status===200&&(this.$toast.success(t.data.message,{position:"top-right",duration:3e3}),this.$router.push("/role"))})}catch(t){handleError(t,this.$toast)}}}},g={key:0},w={key:0},v={class:"table table-bordered"},V=e("th",{class:"col-4 boldText"}," ID ",-1),$={class:"col-4"},C=e("td",{class:"boldText"}," Title ",-1),T=e("td",{class:"boldText"}," Permissions ",-1),x={key:0},E={key:1},R={key:1};function U(r,o,t,B,s,p){const m=u("Unauthorized"),_=u("Loader");return s.loading===!1?(l(),i("div",g,[s.roleView===!0?(l(),i("div",w,[e("h1",null," Role Detail "+n(r.$route.params.id),1),e("table",v,[e("thead",null,[e("tr",null,[V,e("th",$,n(s.roleDetails.id),1)])]),e("tbody",null,[e("tr",null,[C,e("td",null,n(s.roleDetails.name),1)]),e("tr",null,[T,e("td",null,[(l(!0),i(k,null,b(s.roleDetails.permissions,(a,c)=>(l(),i("div",{key:c},[y(n(a.name)+" ",1),c!==s.roleDetails.permissions.length-1?(l(),i("span",x,", ")):d("",!0)]))),128))])])])]),e("button",{onClick:o[0]||(o[0]=a=>r.$router.push({name:"role"})),class:"btn black-bg"},n("Back")),s.roleUpdate?(l(),i("button",{key:0,onClick:o[1]||(o[1]=a=>r.$router.push({path:`/updaterole/${s.roleDetails.id}`})),class:"btn linked-icon"},n("Edit"))):d("",!0),s.roleDelete?(l(),i("button",{key:1,class:"btn linked-icon",onClick:o[2]||(o[2]=a=>p.deleteRole(s.roleDetails.id))}," Delete ")):d("",!0)])):(l(),i("div",E,[h(m)]))])):(l(),i("div",R,[h(_)]))}const L=f(D,[["render",U]]);export{L as default};