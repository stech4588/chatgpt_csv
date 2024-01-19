import{_ as u,r as c,o as l,c as i,d as m,a as o,g as _,h,b,v as w,w as v,t as d}from"./app-8617d8f9.js";const F={name:"template1",data(){return{formData:new this.$form({prompt:"",csvFile:null}),loading:!1,downloadProcessedFileAccess:!1}},methods:{async submitForm(){this.loading=!0;try{const{data:t}=await this.formData.post("/api/send-to-openai");this.$toast.success("Data processed successfully.",{position:"top-right",duration:3e3}),this.downloadProcessedFileAccess=!0}catch(t){handleError(t,this.$toast)}this.loading=!1,this.formData.prompt=""},handleFileChange(t){this.formData.csvFile=t.target.files[0]},async downloadProcessedFile(){try{const t=await this.$axios.get("/api/downloadProcessedFile",{responseType:"blob"}),e=new Blob([t.data]),n=document.createElement("a");n.href=window.URL.createObjectURL(e),n.download="processed_file.csv",n.click()}catch(t){console.error("Error downloading processed file:",t)}}}},y={key:0},g={key:1},x={class:"text-end my-3"},k={class:"mb-3"},D=o("label",{for:"prompt",class:"form-label"},d("Prompt"),-1),C={class:"mb-3"},P=o("label",{for:"csvFile",class:"form-label"},d("CSV File"),-1),V=o("div",{class:"mb-3"},[o("button",{type:"submit",class:"btn btn-primary"},d("RUN"))],-1);function N(t,e,n,B,r,a){const p=c("Loader"),f=c("font-awesome-icon");return r.loading===!0?(l(),i("div",y,[m(p)])):(l(),i("div",g,[o("div",x,[r.downloadProcessedFileAccess?(l(),i("div",{key:0,onClick:e[0]||(e[0]=(...s)=>a.downloadProcessedFile&&a.downloadProcessedFile(...s)),class:"btn linked-icon"},[m(f,{icon:"download","fixed-width":""}),_(" File ")])):h("",!0)]),o("form",{onSubmit:e[3]||(e[3]=v((...s)=>a.submitForm&&a.submitForm(...s),["prevent"])),enctype:"multipart/form-data"},[o("div",k,[D,b(o("input",{"onUpdate:modelValue":e[1]||(e[1]=s=>r.formData.prompt=s),type:"text",class:"form-control",id:"prompt",required:"",autofocus:""},null,512),[[w,r.formData.prompt]])]),o("div",C,[P,o("input",{type:"file",class:"form-control",id:"csvFile",onChange:e[2]||(e[2]=(...s)=>a.handleFileChange&&a.handleFileChange(...s)),accept:".csv"},null,32)]),V],32)]))}const L=u(F,[["render",N]]);export{L as default};