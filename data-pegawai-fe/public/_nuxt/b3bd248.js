(window.webpackJsonp=window.webpackJsonp||[]).push([[9],{287:function(t,e,n){"use strict";n.r(e);var r=n(422).a,o=n(19),component=Object(o.a)(r,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("b-row",{staticClass:"mb-3"},[t._l(t.data,(function(e,i){return[n("b-col",{attrs:{cols:"12",sm:"6",md:"4"}},[n("b-card",{attrs:{title:"Data Usulan "+t.index[i]}},[t.user&&2==t.user.role_id?n("b-row",{staticClass:"mt-3"},[n("b-col",{staticClass:"text-center",attrs:{cols:"4"}},[n("div",[t._v("Total ")]),t._v(" "),n("div",{staticClass:"font-weight-bold fa-2x"},[t._v(t._s(t.data[i].total))])]),t._v(" "),n("b-col",{staticClass:"text-center text-muted",attrs:{cols:"4"}},[n("div",[t._v("Baru ")]),t._v(" "),n("div",{staticClass:"font-weight-bold fa-2x"},[t._v(t._s(t.data[i].baru))])]),t._v(" "),n("b-col",{staticClass:"text-center text-warning",attrs:{cols:"4"}},[n("div",[t._v("Terkirim ")]),t._v(" "),n("div",{staticClass:"font-weight-bold fa-2x"},[t._v(t._s(t.data[i].terkirim))])]),t._v(" "),n("b-col",{staticClass:"text-center text-success",attrs:{cols:"4"}},[n("div",[t._v("Diterima ")]),t._v(" "),n("div",{staticClass:"font-weight-bold fa-2x"},[t._v(t._s(t.data[i].diterima))])]),t._v(" "),n("b-col",{staticClass:"text-center text-primary",attrs:{cols:"4"}},[n("div",[t._v("Terbit ")]),t._v(" "),n("div",{staticClass:"font-weight-bold fa-2x "},[t._v(t._s(t.data[i].terbit))])]),t._v(" "),n("b-col",{staticClass:"text-center text-danger",attrs:{cols:"4"}},[n("div",[t._v("Revisi ")]),t._v(" "),n("div",{staticClass:"font-weight-bold fa-2x"},[t._v(t._s(t.data[i].revisi))])])],1):t._e(),t._v(" "),t.user&&1==t.user.role_id?n("div",[n("pie-chart-usulan",{attrs:{seriesData:t.datachart[i],id:"id"+i}}),t._v(" "),n("b-card-footer",[n("b-row",[n("b-col",{staticClass:"font-weight-bold",attrs:{cols:"5"}},[t._v("Baru:")]),t._v(" "),n("b-col",{staticClass:"text-right",attrs:{cols:"7"}},[t._v(t._s(t.data[i].baru)+" ("+t._s(t._f("formatDesimal")(t.data[i].total>0?t.data[i].baru/t.data[i].total*100:0,1))+"%)")])],1),t._v(" "),n("b-row",[n("b-col",{staticClass:"font-weight-bold",attrs:{cols:"5"}},[t._v("Terkirim:")]),t._v(" "),n("b-col",{staticClass:"text-right text-warning",attrs:{cols:"7"}},[t._v(t._s(t.data[i].terkirim)+" ("+t._s(t._f("formatDesimal")(t.data[i].total>0?t.data[i].terkirim/t.data[i].total*100:0,1))+"%)")])],1),t._v(" "),n("b-row",[n("b-col",{staticClass:"font-weight-bold",attrs:{cols:"5"}},[t._v("Diterima:")]),t._v(" "),n("b-col",{staticClass:"text-right text-success",attrs:{cols:"7"}},[t._v(t._s(t.data[i].diterima)+" ("+t._s(t._f("formatDesimal")(t.data[i].total>0?t.data[i].diterima/t.data[i].total*100:0,1))+"%)")])],1),t._v(" "),n("b-row",[n("b-col",{staticClass:"font-weight-bold",attrs:{cols:"5"}},[t._v("Terbit:")]),t._v(" "),n("b-col",{staticClass:"text-right text-primary",attrs:{cols:"7"}},[t._v(t._s(t.data[i].terbit)+" ("+t._s(t._f("formatDesimal")(t.data[i].total>0?t.data[i].terbit/t.data[i].total*100:0,1))+"%)")])],1),t._v(" "),n("b-row",[n("b-col",{staticClass:"font-weight-bold",attrs:{cols:"5"}},[t._v("Revisi:")]),t._v(" "),n("b-col",{staticClass:"text-right text-danger",attrs:{cols:"7"}},[t._v(t._s(t.data[i].revisi)+" ("+t._s(t._f("formatDesimal")(t.data[i].total>0?t.data[i].revisi/t.data[i].total*100:0,1))+"%)")])],1)],1)],1):t._e()],1)],1)]}))],2),t._v(" "),n("h3",{staticClass:"text-primary font-weight-bold mb-4"},[t._v("Daftar Layanan")]),t._v(" "),n("b-row",t._l(t.layanans,(function(e,r){return n("b-col",{key:r,staticClass:"mb-3 col-lg-3 col-md-6"},[n("div",{staticClass:"card text-center mb-0 h-100"},[n("nuxt-link",{staticClass:"card-body",attrs:{to:e.link}},[n("i",{staticClass:"bx mb-4 h1 text-primary",class:e.icon}),t._v(" "),n("h5",{staticClass:"font-size-15 text-uppercase"},[t._v("\n              "+t._s(e.nama)+"\n            ")]),t._v(" "),n("p",{staticClass:"text-muted mb-0",domProps:{innerHTML:t._s(e.deskripsi)}})])],1)])})),1)],1)}),[],!1,null,null,null);e.default=component.exports},422:function(t,e,n){"use strict";(function(t){n(106),n(79),n(78),n(33),n(155),n(70),n(156);var r=n(102),o=n(22),l=(n(428),n(50),n(38)),c=n.n(l),d=n(43),v=n(272);function m(object,t){var e=Object.keys(object);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(object);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(object,t).enumerable}))),e.push.apply(e,n)}return e}e.a={components:{PieChartUsulan:v.default},head:function(){return{title:this.$t("dashboard")}},asyncData:function(){return Object(o.a)(regeneratorRuntime.mark((function e(){var n,r;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,c.a.get("pengajuan/statistik");case 2:return n=e.sent.data,console.log(n),r=[],t.each(n.data,(function(t,e){console.log("index",t),r.push([{name:"Terbit",y:e.terbit},{name:"Baru",y:e.baru},{name:"Terkirim",y:e.terkirim},{name:"Diterima",y:e.diterima},{name:"Revisi",y:e.revisi}])})),e.abrupt("return",{index:n.index,data:n.data,datachart:r});case 7:case"end":return e.stop()}}),e)})))()},computed:function(t){for(var i=1;i<arguments.length;i++){var source=null!=arguments[i]?arguments[i]:{};i%2?m(Object(source),!0).forEach((function(e){Object(r.a)(t,e,source[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(source)):m(Object(source)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(source,e))}))}return t}({},Object(d.b)({user:"auth/user"})),filters:{formatDesimal:function(t,e){if(!t)return"0";if(null==t||""==t)return"-";var n=parseFloat(t).toFixed(e);return 0==n?"-":n}},data:function(){return{layanans:[{nama:"Rekomendasi Pelantikan JPTP",deskripsi:"Ajukan rekomendasi pelantikan jabatan pimpinan tinggi pratama",icon:"bx-building",link:"/list-usulan-camat"},{nama:"Rekomendasi Camat",deskripsi:"Ajukan rekomendasi pelantikan camat",icon:"bxs-bank",link:"/list-usulan-pengangkatan-inspektur"},{nama:"Rekomendasi Pelantikan JA dan JP",deskripsi:"Ajukan rekomendasi pelantikan jabatan administrator dan jabatan pengawas",icon:"bxs-edit-alt",link:"/list-usulan-panitia-seleksi-inspektur"},{nama:"Rekomendasi Inspektorat",deskripsi:"Ajukan rekomendasi pelantikan Inspektur atau Inspektur Pembantu",icon:"bx-buildings",link:"/list-usulan-pelantikan-jptp"},{nama:"Permohonan Izin Uji Kompetensi / Seleksi Terbuka",deskripsi:"Ajukan Permohonan Izin Uji Kompetensi / Seleksi Terbuka",icon:"bx-news",link:"/list-usulan-pelantikan-ja-jp"},{nama:"Permohonan Pejabat Pemprov sebagai Panitia Seleksi Terbuka JPTP",deskripsi:"Ajukan Permohonan Pejabat Pemprov sebagai Panitia Seleksi Terbuka JPTP",icon:"bxs-envelope",link:"/list-usulan-uji-kompetensi"},{nama:"Penggantian JPTP Sekretaris Daerah",deskripsi:"Ajukan Penggantian JPTP Sekretaris Daerah",icon:"bx-street-view",link:"/list-usulan-penggantian-jptp-sekda"},{nama:"Rekomendasi Pansel Inspektur",deskripsi:"Ajukan rekomendasi panitia seleksi untuk inspektur",icon:"bxs-user-detail",link:"/list-usulan-panitia-seleksi-jptp"}]}}}}).call(this,n(267))},428:function(t,e,n){"use strict";var r=n(21),o=n(14),l=n(17),c=n(58),d=n(429),v=n(196),m=n(18),f=o.RangeError,k=o.String,_=Math.floor,x=l(v),h=l("".slice),w=l(1..toFixed),j=function(t,e,n){return 0===e?n:e%2==1?j(t,e-1,n*t):j(t*t,e/2,n)},P=function(data,t,e){for(var n=-1,r=e;++n<6;)r+=t*data[n],data[n]=r%1e7,r=_(r/1e7)},C=function(data,t){for(var e=6,n=0;--e>=0;)n+=data[e],data[e]=_(n/t),n=n%t*1e7},y=function(data){for(var t=6,s="";--t>=0;)if(""!==s||0===t||0!==data[t]){var e=k(data[t]);s=""===s?e:s+x("0",7-e.length)+e}return s};r({target:"Number",proto:!0,forced:m((function(){return"0.000"!==w(8e-5,3)||"1"!==w(.9,0)||"1.25"!==w(1.255,2)||"1000000000000000128"!==w(0xde0b6b3a7640080,0)}))||!m((function(){w({})}))},{toFixed:function(t){var e,n,r,o,l=d(this),v=c(t),data=[0,0,0,0,0,0],m="",_="0";if(v<0||v>20)throw f("Incorrect fraction digits");if(l!=l)return"NaN";if(l<=-1e21||l>=1e21)return k(l);if(l<0&&(m="-",l=-l),l>1e-21)if(n=(e=function(t){for(var e=0,n=t;n>=4096;)e+=12,n/=4096;for(;n>=2;)e+=1,n/=2;return e}(l*j(2,69,1))-69)<0?l*j(2,-e,1):l/j(2,e,1),n*=4503599627370496,(e=52-e)>0){for(P(data,0,n),r=v;r>=7;)P(data,1e7,0),r-=7;for(P(data,j(10,r,1),0),r=e-1;r>=23;)C(data,1<<23),r-=23;C(data,1<<r),P(data,1,1),C(data,2),_=y(data)}else P(data,0,n),P(data,1<<-e,0),_=y(data)+x("0",v);return _=v>0?m+((o=_.length)<=v?"0."+x("0",v-o)+_:h(_,0,o-v)+"."+h(_,o-v)):m+_}})},429:function(t,e,n){var r=n(17);t.exports=r(1..valueOf)}}]);