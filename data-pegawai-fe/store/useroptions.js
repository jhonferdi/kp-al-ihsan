import axios from "axios";
import Cookies from "js-cookie";

// state
export const state = () => ({
  PEGOptions: [],
  KPOptions: [],
  BIDOptions: [],
  PDOptions: [],
  UKEROptions: [],
  RUANGANOptions: [],
  SPESOptions: [],
  JBOptions: [],
  JJOptions: [],
  GOLOptions: [],
  JTKOptions: [],
  // JFOptions: [],
  SPEGOptions: [],
  PROVOptions: [],
});

// getters
export const getters = {
  PEGOptions: (state) => state.PEGOptions,
  KPOptions: (state) => state.KPOptions,
  BIDOptions: (state) => state.BIDOptions,
  PDOptions: (state) => state.PDOptions,
  UKEROptions: (state) => state.UKEROptions,
  RUANGANOptions: (state) => state.RUANGANOptions,
  SPESOptions: (state) => state.SPESOptions,
  JBOptions: (state) => state.JBOptions,
  JJOptions: (state) => state.JJOptions,
  GOLOptions: (state) => state.GOLOptions,
  JTKOptions: (state) => state.JTKOptions,
  // JFOptions: (state) => state.JFOptions,
  SPEGOptions: (state) => state.SPEGOptions,
  PROVOptions: (state) => state.PROVOptions,
};

// mutations
export const mutations = {
  SET_PEGOptions(state, PEGOptions) {
    state.PEGOptions = PEGOptions;
  },
  SET_KABOptions(state, KABOptions) {
    state.KABOptions = KABOptions;
  },
  SET_KECOptions(state, KECOptions) {
    state.KECOptions = KECOptions;
  },
  SET_KELOptions(state, KELOptions) {
    state.KELOptions = KELOptions;
  },
  SET_KPOptions(state, KPOptions) {
    state.KPOptions = KPOptions;
  },
  SET_BIDOptions(state, BIDOptions) {
    state.BIDOptions = BIDOptions;
  },
  SET_PDOptions(state, PDOptions) {
    state.PDOptions = PDOptions;
  },
  SET_UKEROptions(state, UKEROptions) {
    state.UKEROptions = UKEROptions;
  },
  SET_RUANGANOptions(state, RUANGANOptions) {
    state.RUANGANOptions = RUANGANOptions;
  },
  SET_SPESOptions(state, SPESOptions) {
    state.SPESOptions = SPESOptions;
  },
  SET_JBOptions(state, JBOptions) {
    state.JBOptions = JBOptions;
  },
  SET_JJOptions(state, JJOptions) {
    state.JJOptions = JJOptions;
  },
  SET_GOLOptions(state, GOLOptions) {
    state.GOLOptions = GOLOptions;
  },
  SET_JTKOptions(state, JTKOptions) {
    state.JTKOptions = JTKOptions;
  },
  // SET_JFOptions(state, JFOptions) {
  //   state.JFOptions = JFOptions;
  // },
  SET_SPEGOptions(state, SPEGOptions) {
    state.SPEGOptions = SPEGOptions;
  },
  SET_PROVOptions(state, PROVOptions) {
    state.PROVOptions = PROVOptions;
  },
};

// actions
export const actions = {
  async fetchOptions({ commit }) {
    axios.get("/pegawai/options").then((resp) => {
      // Pegawai
      let placeholderPeg = [
        { peg_id: null, peg_nama_lengkap: "Pilih Pegawai" },
      ];
      let PEGOptions = placeholderPeg.concat(resp.data.pegawai);
      commit("SET_PEGOptions", PEGOptions);
      // Kualifikasi Pendidikan
      let placeholderKuPel = [
        {
          kualifikasi_pendidikan_id: null,
          kualifikasi_pendidikan: "Pilih Tingkat Pendidikan",
        },
      ];
      let KPOptions = placeholderKuPel.concat(resp.data.kualifikasi_pendidikan);
      commit("SET_KPOptions", KPOptions);
      // Bidang
      let placeholderBid = [{ bidang_id: null, bidang_nama: "Pilih Bidang" }];
      let BIDOptions = placeholderBid.concat(resp.data.bidang);
      commit("SET_BIDOptions", BIDOptions);
      // Pendidikan
      let placeholderPend = [
        { pendidikan_id: null, pendidikan_nama: "Pilih Pendidikan" },
      ];
      let PDOptions = placeholderPend.concat(resp.data.pendidikan);
      commit("SET_PDOptions", PDOptions);
      // Unit Kerja
      let placeholderUKer = [
        { unit_kerja_id: null, unit_kerja_nama: "Pilih Unit Kerja" },
      ];
      let UKEROptions = placeholderUKer.concat(resp.data.unit_kerja);
      commit("SET_UKEROptions", UKEROptions);
      // Ruangan
      let placeholderRuangan = [
        { ruangan_id: null, nama_ruangan: "Pilih Ruangan" },
      ];
      let RUANGANOptions = placeholderRuangan.concat(resp.data.ruangan);
      commit("SET_RUANGANOptions", RUANGANOptions);
      // Spesialis
      let placeholderSp = [
        { spesialis_id: null, spesialis_nama: "Pilih Spesialis" },
      ];
      let SPESOptions = placeholderSp.concat(resp.data.spesialis);
      commit("SET_SPESOptions", SPESOptions);
      // Jabatan
      let placeholderJab = [
        { jabatan_id: null, jabatan_nama: "Pilih Jabatan" },
      ];
      let JBOptions = placeholderJab.concat(resp.data.jabatan);
      commit("SET_JBOptions", JBOptions);
      // Jenis Jabatan
      let placeholderJenJab = [
        { jenis_jabatan_id: null, jenis_jabatan_nama: "Pilih Jenis Jabatan" },
      ];
      let JJOptions = placeholderJenJab.concat(resp.data.jenis_jabatan);
      commit("SET_JJOptions", JJOptions);
      // Golongan
      let placeholderGol = [
        { golongan_id: null, golongan_nama: "Pilih Golongan" },
      ];
      let GOLOptions = placeholderGol.concat(resp.data.golongan);
      commit("SET_GOLOptions", GOLOptions);
      // Jabatan Fungsional
      // let placeholderJabFung = [
      //   {
      //     jabatan_fungsional_id: null,
      //     jabatan_fungsional_nama: "Pilih Jabatan Fungsional",
      //   },
      // ];
      // let JFOptions = placeholderJabFung.concat(resp.data.jabatan_fungsional);
      // commit("SET_JFOptions", JFOptions);
      //Jenis Tenaga Kerja
      let placeholderTenKer = [
        {
          jenis_tenaga_kerja_id: null,
          jenis_tenaga_kerja_nama: "Pilih Kualifikasi Pendidikan Tenaga Kerja",
        },
      ];
      let JTKOptions = placeholderTenKer.concat(resp.data.jenis_tenaga_kerja);
      commit("SET_JTKOptions", JTKOptions);
      // Status Pegawai
      let placeholderSPeg = [
        {
          status_kepegawaian_id: null,
          status_kepegawaian: "Pilih Status Pegawai",
        },
      ];
      let SPEGOptions = placeholderSPeg.concat(resp.data.status_pegawai);
      commit("SET_SPEGOptions", SPEGOptions);
      // Provinsi
      let placeholderProv = [{ id: null, nama: "Pilih Provinsi" }];
      let PROVOptions = placeholderProv.concat(resp.data.provinsi);
      commit("SET_PROVOptions", PROVOptions);
    });
  },
};
