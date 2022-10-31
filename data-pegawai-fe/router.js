import Vue from "vue";
import Router from "vue-router";
import { scrollBehavior } from "~/utils";

Vue.use(Router);

const page = (path) => () =>
  import(`~/pages/${path}`).then((m) => m.default || m);

const routes = [
  // { path: '/', name: 'welcome', component: page('welcome.vue') },
  { path: "/", name: "welcome", component: page("auth/login.vue") },

  { path: "/login", name: "login", component: page("auth/login.vue") },

  {
    path: "/password/email",
    name: "password.email",
    component: page("auth/password/email.vue"),
  },

  {
    path: "/password/reset/:token",
    name: "password.reset",
    component: page("auth/password/reset.vue"),
  },

  { path: "/dashboard", name: "dashboard", component: page("dashboard.vue") },

  {
    path: "/spesialis",
    name: "spesialis",
    component: page("spesialis/index.vue"),
  },

  {
    path: "/sub-spesialis",
    name: "sub_spesialis",
    component: page("sub-spesialis/index.vue"),
  },

  { path: "/bidang", name: "bidang", component: page("bidang/index.vue") },

  { path: "/jabatan", name: "jabatan", component: page("jabatan/index.vue") },

  { path: "/ruangan", name: "ruangan", component: page("ruangan/index.vue") },

  {
    path: "/golongan",
    name: "golongan",
    component: page("golongan/index.vue"),
  },

  {
    path: "/pendidikan",
    name: "pendidikan",
    component: page("pendidikan/index.vue"),
  },

  {
    path: "/kepegawaian",
    name: "kepegawaian",
    component: page("kepegawaian/index.vue"),
  },

  {
    path: "/unit-kerja",
    name: "unit-kerja",
    component: page("unit-kerja/index.vue"),
  },

  // { path: "/pegawai", name: "pegawai", component: page("kepegawaian/index.vue") },

  {
    path: "/pegawai/create",
    name: "pegawai.create",
    component: page("kepegawaian/create.vue"),
  },
  {
    path: "/pegawai/:id",
    name: "pegawai.view",
    component: page("kepegawaian/view.vue"),
  },

  { path: "/user-data", name: "user", component: page("user/index.vue") },
  {
    path: "/user-data/create",
    name: "user.create",
    component: page("user/create.vue"),
  },
  {
    path: "/user-data/:id/edit",
    name: "user.edit",
    component: page("user/edit.vue"),
  },

  {
    path: "/profile/",
    name: "settings.profile",
    component: page("kepegawaian/view.vue"),
  },

  {
    path: "/profile/password",
    name: "settings.password",
    component: page("settings/password.vue"),
  },
  {
    path: "/action-log",
    name: "action-log",
    component: page("action-log/index.vue"),
  },
  {
    path: "/rekapitulasi-pegawai",
    name: "rekapitulasi-pegawai",
    component: page("rekapitulasi-pegawai/index.vue"),
  },
];

export function createRouter() {
  return new Router({
    routes,
    scrollBehavior,
    mode: "history",
    base: process.env.baseRoute || "/",
  });
}
