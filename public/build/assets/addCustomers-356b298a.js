import{_ as b,r as _,o as d,c as i,a as o,w,b as a,v as u,n as f,d as m,t as n,l as y,F as x,j as g}from"./app-8617d8f9.js";const k={inject:["authStore"],name:"Add",data(){return{form:new this.$form({name:"",email:"",password:"",phone_no:"",role_id:2}),roles:[],addedSuccessful:!1,loading:!0,customersAdd:!1}},async mounted(){if(this.authStore.user===null)this.loading=!0;else{const r=["customersAdd"],e=await checkPermissions(r);r.forEach(l=>{e[l]&&(this[l]=!0,l==="customersAdd"&&(this.loading=!1))});try{const l=await this.$axios.get("/api/customer/create");this.roles=l.data.data}catch(l){handleError(l,this.$toast)}}},methods:{async addInRole(){try{const{data:r}=await this.form.post("/api/customer");r.status===200&&(this.$toast.success(r.message,{position:"top-right",duration:3e3}),await this.$router.push("/customers"))}catch(r){handleError(r,this.$toast)}}}},U={key:0},A={key:0},V=o("h1",null," Add User ",-1),C={class:"row"},S={class:"col-lg-12 m-auto"},q={class:"mb-3 row"},E=o("label",{class:"col-md-3 col-form-label text-md-end"},n("Name"),-1),R={class:"col-md-7"},z={class:"mb-3 row"},B=o("label",{class:"col-md-3 col-form-label text-md-end"},n("Email"),-1),I={class:"col-md-7"},L={class:"mb-3 row"},M=o("label",{class:"col-md-3 col-form-label text-md-end"},n("Password"),-1),N={class:"col-md-7"},P={class:"mb-3 row"},j={class:"col-md-3 col-form-label text-md-end"},D={class:"col-md-7"},F={class:"mb-3 row"},K=o("label",{class:"col-md-3 col-form-label text-md-end"},n("Role"),-1),T={class:"col-md-7"},G=["value"],H={class:"mb-3 row"},J={class:"col-md-7 offset-md-3 d-flex"},O=["disabled"],Q={key:1},W={key:1};function X(r,e,l,Y,s,h){const c=_("has-error"),p=_("Unauthorized"),v=_("Loader");return s.loading===!1?(d(),i("div",U,[s.customersAdd===!0?(d(),i("div",A,[V,o("div",C,[o("div",S,[o("form",{onSubmit:e[6]||(e[6]=w((...t)=>h.addInRole&&h.addInRole(...t),["prevent"])),onKeydown:e[7]||(e[7]=t=>s.form.onKeydown(t))},[o("div",q,[E,o("div",R,[a(o("input",{"onUpdate:modelValue":e[0]||(e[0]=t=>s.form.name=t),class:f([{"is-invalid":s.form.errors.has("name")},"form-control"]),type:"text",name:"name",required:""},null,2),[[u,s.form.name]]),m(c,{form:s.form,field:"name"},null,8,["form"])])]),o("div",z,[B,o("div",I,[a(o("input",{"onUpdate:modelValue":e[1]||(e[1]=t=>s.form.email=t),class:f([{"is-invalid":s.form.errors.has("email")},"form-control"]),type:"text",name:"email",required:""},null,2),[[u,s.form.email]]),m(c,{form:s.form,field:"email"},null,8,["form"])])]),o("div",L,[M,o("div",N,[a(o("input",{"onUpdate:modelValue":e[2]||(e[2]=t=>s.form.password=t),class:f([{"is-invalid":s.form.errors.has("password")},"form-control"]),type:"text",name:"password",required:""},null,2),[[u,s.form.password]]),m(c,{form:s.form,field:"password"},null,8,["form"])])]),o("div",P,[o("label",j,n("Phone no."),1),o("div",D,[a(o("input",{"onUpdate:modelValue":e[3]||(e[3]=t=>s.form.phone_no=t),class:f([{"is-invalid":s.form.errors.has("phone_no")},"form-control"]),type:"text",name:"phone_no",required:""},null,2),[[u,s.form.phone_no]]),m(c,{form:s.form,field:"phone_no"},null,8,["form"])])]),o("div",F,[K,o("div",T,[a(o("select",{"onUpdate:modelValue":e[4]||(e[4]=t=>s.form.role_id=t),class:"form-control",name:"role_id",required:""},[(d(!0),i(x,null,g(s.roles,t=>(d(),i("option",{key:t.id,value:t.id},n(t.name),9,G))),128))],512),[[y,s.form.role_id]])])]),o("div",H,[o("div",J,[o("button",{class:"btn grey-bg",disabled:s.form.busy},n("Add"),8,O),o("button",{onClick:e[5]||(e[5]=t=>r.$router.push({name:"role"})),class:"btn black-bg ms-1"},n("Cancel"))])])],32)])])])):(d(),i("div",Q,[m(p)]))])):(d(),i("div",W,[m(v)]))}const $=b(k,[["render",X]]);export{$ as default};