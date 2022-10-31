<template>
  <div>
    <PageHeaderV2 :title="`${$metaInfo.title}`"
      :description="'Manajemen unit kerja dan sub unit kerja di lingkungan rumah sakit Al Ihsan'" :items="[
        { text: $metaInfo.title, active: true },
      ]" :imageUrl="'unit-kerja.svg'" />
    <div class="card -mx-content border-top mb-0 p-2" style="border-color:rgba(224, 224, 224, 1) !important">
      <div class="card-body">
        <div class="d-sm-inline-flex mb-3">
          <b-input-group class="mb-2 mb-sm-0">
            <b-form-input ref="search" @keyup.enter="updateSearch()" v-model="ctx.filter" placeholder="Cari unit kerja">
            </b-form-input>
            <b-input-group-append>
              <b-button variant="darkgreen" @click="updateSearch()">
                <b-icon icon="search" />
              </b-button>
            </b-input-group-append>
          </b-input-group>
        </div>
        <b-row>
          <b-col md="4" class="mb-3">
            <div class="border rounded h-100">
              <div style="border-left:5px solid"
                class="h-100 rounded d-flex flex-column justify-content-between border-darkgreen p-3">
                <div class="mb-3">
                  <label>Pilih Wakil Direktur</label>
                  <b-form-select class="font-size-13" v-model="form.wd_id" :options="WDOptions"
                    value-field="unit_kerja_id" text-field="unit_kerja_nama">
                  </b-form-select>
                </div>
                <div class="mb-0">
                  <label>Tambah Unit Kerja</label>
                  <b-form-input placeholder="Masukkan nama unit kerja baru" v-model="form.unit_kerja_nama"
                    :state="getErrorState('unit_kerja_nama')">
                  </b-form-input>
                  <p style="color:red;" v-if="getErrorState('unit_kerja_nama') === false">
                    {{ getErrorMessage('unit_kerja_nama') }}
                  </p>
                </div>
                <div class="mt-3 text-right">
                  <b-button class="ml-auto px-4" variant="darkgreen" @click="saveUnitKerja"
                    :disabled="loadingUnitKerja">
                    {{ loadingUnitKerja ? 'Sedang Menambah' : 'Tambah' }}
                  </b-button>
                </div>
              </div>
            </div>
          </b-col>
          <b-col md="4" class="mb-3">
            <div class="border rounded h-100">
              <div style="border-left:5px solid"
                class="h-100 rounded d-flex flex-column justify-content-between border-primary p-3">
                <div class="mb-3">
                  <label>Pilih Unit Kerja</label>
                  <b-form-select class="font-size-13" v-model="form.unit_kerja_id" :options="UKEROptions"
                    value-field="unit_kerja_id" text-field="unit_kerja_nama">
                  </b-form-select>
                </div>
                <div class="mb-0">
                  <label>Tambah Instalasi</label>
                  <b-form-input placeholder="Masukkan nama instalasi baru" v-model="form.nama_instalasi"
                    :state="getErrorState('nama_instalasi')"></b-form-input>
                  <p style="color:red;" v-if="getErrorState('nama_instalasi') === false">
                    {{ getErrorMessage('nama_instalasi') }}
                  </p>
                </div>
                <div class="mt-3 text-right">
                  <b-button class="ml-auto px-4" variant="darkgreen" @click="saveInstalasi"
                    :disabled="loadingInstalasi">
                    {{ loadingInstalasi ? 'Sedang Menambah' : 'Tambah' }}
                  </b-button>
                </div>
              </div>
            </div>
          </b-col>
          <b-col md="4" class="mb-3">
            <div class="border rounded h-100">
              <div style="border-left:5px solid"
                class="h-100 rounded d-flex flex-column justify-content-between border-warning p-3">
                <div class="mb-3">
                  <label>Pilih Instalasi</label>
                  <b-form-select class="font-size-13" v-model="form.instalasi_id" :options="INSOptions"
                    value-field="ruangan_id" text-field="nama_ruangan">
                  </b-form-select>
                </div>
                <div class="mb-0">
                  <label>Tambah Ruangan</label>
                  <b-form-input placeholder="Masukkan nama ruangan baru" v-model="form.nama_ruangan"
                    :state="getErrorState('nama_ruangan')"></b-form-input>
                  <p style="color:red;" v-if="getErrorState('nama_ruangan') === false">
                    {{ getErrorMessage('nama_ruangan') }}
                  </p>
                </div>
                <div class="mt-3 text-right">
                  <b-button class="ml-auto px-4" variant="darkgreen" @click="saveRuangan" :disabled="loadingRuangan">
                    {{ loadingRuangan ? 'Sedang Menambah' : 'Tambah' }}
                  </b-button>
                </div>
              </div>
            </div>
          </b-col>
        </b-row>
        <div>
          <ModalUnitKerja :open="modalUnitOpen" :unitKerja="unitKerjaData" :WDOptions="WDOptions" size="xs"
            :title="`${title} Unit Kerja`" @close="modalUnitOpen = false" @onSave="refreshData" />
          <ModalInstalasi :open="modalInstalasiOpen" :instalasi="instalasiData" :UKEROptions="UKEROptions" size="xs"
            :title="`${title} Instalasi`" @close="modalInstalasiOpen = false" @onSave="refreshData" />
          <ModalRuangan :open="modalRuanganOpen" :ruangan="ruanganData" :INSOptions="INSOptions" size="xs"
            :title="`${title} Ruangan`" @close="modalRuanganOpen = false" @onSave="refreshData" />
          <template v-for="(unit, i) in unit_kerja" v-if="unit.unit_kerja_level == 2">
            <div class="border rounded mb-3 shadow-sm">
              <div style="border-left:5px solid"
                class="h-100 rounded border-darkgreen p-2 px-3 align-items-center d-flex justify-content-between"
                v-b-toggle="'unit-kerja' + i">
                <div>{{ unit.unit_kerja_nama }}</div>
                <div class="d-flex">
                  <div class="rounded px-2 py-1 text-muted mr-2 mr-sm-0" style="background:#EEEEEE">
                    {{ unit.ruangan.filter(x => x.ruangan_level == 1).length }} Instalasi
                  </div>
                  <div class="mx-3 d-none d-sm-block border-left flex-shrink-0" style="border-color:#E0E0E0 !important">
                  </div>
                  <a href="#" @click.prevent="openModalUnitKerjaEdit(unit)"
                    class="text-darkblue p-1 d-inline-flex align-items-center bg-softblue rounded" @click.stop>
                    <i class="mb-0 bx bx-edit-alt" style="font-size:18px"></i>
                  </a>
                  <b-link size="sm" class="text-danger p-1 d-inline-flex align-items-center bg-pink-new rounded"
                    @click="onDeleteUnitKerja = unit; modalHapusUnitKerjaOpen = true" @click.stop>
                    <i class="mb-0 bx bx-trash" style="font-size:18px"></i>
                  </b-link>
                </div>
              </div>
            </div>
            <b-collapse :id="'unit-kerja' + i">
              <div class="d-flex align-items-baseline" v-for="(instalasi, i) in unit.ruangan"
                v-if="instalasi.ruangan_level == 1" v-b-toggle="'instalasi' + i">
                <div class="h3 mr-2">
                  <svg width="19" height="12" viewBox="0 0 19 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M17.59 8.32001H3.75C1.68 8.32001 0 6.64001 0 4.57001V1.25C0 0.84 0.34 0.5 0.75 0.5C1.16 0.5 1.5 0.84 1.5 1.25V4.57001C1.5 5.81001 2.51 6.82001 3.75 6.82001H17.59C18 6.82001 18.34 7.16001 18.34 7.57001C18.34 7.98001 18 8.32001 17.59 8.32001Z"
                      fill="#212121" />
                    <path
                      d="M14.4301 11.4806C14.2401 11.4806 14.0501 11.4106 13.9001 11.2606C13.6101 10.9706 13.6101 10.4905 13.9001 10.2005L16.5301 7.57059L13.9001 4.94058C13.6101 4.65058 13.6101 4.17059 13.9001 3.88059C14.1901 3.59059 14.6701 3.59059 14.9601 3.88059L18.1201 7.04056C18.2601 7.18056 18.3401 7.37059 18.3401 7.57059C18.3401 7.77059 18.2601 7.96056 18.1201 8.10056L14.9601 11.2606C14.8201 11.4106 14.6301 11.4806 14.4301 11.4806Z"
                      fill="#212121" />
                  </svg>
                </div>
                <div class="w-100">
                  <div class="border rounded mb-3 shadow-sm">
                    <div style="border-left:5px solid"
                      class="h-100 rounded border-primary p-2 px-3 align-items-center d-flex justify-content-between">
                      <div>{{ instalasi.nama_ruangan }}</div>
                      <div class="d-flex">
                        <div class="rounded px-2 py-1 text-muted mr-2 mr-sm-0" style="background:#EEEEEE">
                          {{ instalasi.parent.filter(x => x.ruangan_level == 2).length }} Ruangan
                        </div>
                        <div class="mx-3 d-none d-sm-block border-left flex-shrink-0"
                          style="border-color:#E0E0E0 !important"></div>
                        <a href="#" @click.prevent="openModalInstalasiEdit(instalasi)"
                          class="text-darkblue p-1 mr-2 d-inline-flex align-items-center bg-softblue rounded"
                          @click.stop>
                          <i class="mb-0 bx bx-edit-alt" style="font-size:18px"></i>
                        </a>
                        <b-link size="sm" class="text-danger p-1 d-inline-flex align-items-center bg-pink-new rounded"
                          @click="onDeleteInstalasi = instalasi; modalHapusInstalasiOpen = true" @click.stop>
                          <i class="mb-0 bx bx-trash" style="font-size:18px"></i>
                        </b-link>
                      </div>
                    </div>
                  </div>
                  <b-collapse :id="'instalasi' + i">
                    <div class="d-flex align-items-baseline" v-for="ruangan in instalasi.parent"
                      v-if="instalasi.parent.length > 0">
                      <div class="h3 mr-2">
                        <svg width="19" height="12" viewBox="0 0 19 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path
                            d="M17.59 8.32001H3.75C1.68 8.32001 0 6.64001 0 4.57001V1.25C0 0.84 0.34 0.5 0.75 0.5C1.16 0.5 1.5 0.84 1.5 1.25V4.57001C1.5 5.81001 2.51 6.82001 3.75 6.82001H17.59C18 6.82001 18.34 7.16001 18.34 7.57001C18.34 7.98001 18 8.32001 17.59 8.32001Z"
                            fill="#212121" />
                          <path
                            d="M14.4301 11.4806C14.2401 11.4806 14.0501 11.4106 13.9001 11.2606C13.6101 10.9706 13.6101 10.4905 13.9001 10.2005L16.5301 7.57059L13.9001 4.94058C13.6101 4.65058 13.6101 4.17059 13.9001 3.88059C14.1901 3.59059 14.6701 3.59059 14.9601 3.88059L18.1201 7.04056C18.2601 7.18056 18.3401 7.37059 18.3401 7.57059C18.3401 7.77059 18.2601 7.96056 18.1201 8.10056L14.9601 11.2606C14.8201 11.4106 14.6301 11.4806 14.4301 11.4806Z"
                            fill="#212121" />
                        </svg>
                      </div>
                      <div class="w-100">
                        <div class="border rounded mb-3 shadow-sm">
                          <div style="border-left:5px solid"
                            class="h-100 rounded border-warning p-2 px-3 align-items-center d-flex justify-content-between">
                            <div>{{ ruangan.nama_ruangan }}
                            </div>
                            <div class="d-flex">
                              <div class="mx-3 d-none d-sm-block border-left flex-shrink-0"
                                style="border-color:#E0E0E0 !important"></div>
                              <a href="#" @click.prevent="openModalRuanganEdit(ruangan)"
                                class="text-darkblue p-1 mr-2 d-inline-flex align-items-center bg-softblue rounded"
                                @click.stop>
                                <i class="mb-0 bx bx-edit-alt" style="font-size:18px"></i>
                              </a>
                              <b-link size="sm"
                                class="text-danger p-1 d-inline-flex align-items-center bg-pink-new rounded"
                                @click="onDeleteRuangan = ruangan; modalHapusRuanganOpen = true" @click.stop>
                                <i class="mb-0 bx bx-trash" style="font-size:18px"></i>
                              </b-link>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </b-collapse>
                </div>
              </div>
            </b-collapse>
          </template>
        </div>
        <modal-hapus name="unit kerja" :open="modalHapusUnitKerjaOpen" @close="modalHapusUnitKerjaOpen = false"
          @delete="deleteUnitKerja" />
        <modal-hapus name="instalasi" :open="modalHapusInstalasiOpen" @close="modalHapusInstalasiOpen = false"
          @delete="deleteInstalasi" />
        <modal-hapus name="ruangan" :open="modalHapusRuanganOpen" @close="modalHapusRuanganOpen = false"
          @delete="deleteRuangan" />
        <TablePagination v-model="ctx.currentPage" :itemsLength="unit_kerja_count" @perPage="getPerPage" />
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import ModalUnitKerja from '~/components/modals/unit-kerja/ModalUnitKerja.vue'
import ModalInstalasi from '~/components/modals/unit-kerja/ModalInstalasi.vue'
import ModalRuangan from '~/components/modals/unit-kerja/ModalRuangan.vue'
import { buildQueryParams } from '~/plugins/utils'
import { filterBySearch } from '~/assets/js/helpers'
import { _ } from 'vue-underscore'

export default {
  middleware: ["auth"],
  head() {
    return { title: "Unit Kerja" };
  },
  async asyncData() {
    if (window.tablectxuker) {
      var ctx = window.tablectxuker;
    }
    else {
      var ctx = {
        currentPage: 1,
        perPage: 20,
        filter: "",
      };
    }
    let f1resp = (await axios.get("/unit-kerja?" + buildQueryParams(ctx))).data;
    let unit_kerja = f1resp.data;
    let unit_kerja_count = f1resp.count;
    let WDOptions = unit_kerja.filter(x => x.unit_kerja_level == 1);
    let UKEROptions = unit_kerja.filter(x => x.unit_kerja_level == 2);
    let tmp = _.clone(unit_kerja)
    return {
      unit_kerja,
      unit_kerja_count,
      ctx,
      WDOptions,
      UKEROptions,
      tmp
    };
  },
  data: () => ({
    fields: [
      { key: "unit_kerja_nama", label: "Nama Unit Kerja", sortable: true, sortDirection: "asc" },
      { key: "unit_kerja_level", label: "Unit Kerja Level", sortable: false, sortDirection: "asc" },
      { key: "unit_kerja_parent", label: "Unit Kerja Parent", sortable: false, sortDirection: "asc" },
      { key: "actions", label: "Actions" }
    ],
    form: {},
    loadingUnitKerja: false,
    loadingInstalasi: false,
    loadingRuangan: false,
    isTableInit: false,
    isBusy: false,
    count_instalasi: 0,
    INSOptions: [],
    modalUnitOpen: false,
    modalInstalasiOpen: false,
    modalRuanganOpen: false,
    unitKerjaData: null,
    instalasiData: null,
    ruanganData: null,
    title: null,
    modalHapusUnitKerjaOpen: false,
    onDeleteUnitKerja: null,
    modalHapusInstalasiOpen: false,
    onDeleteInstalasi: null,
    modalHapusRuanganOpen: false,
    onDeleteRuangan: null,
    errors: {}
  }),
  mounted() {
    let arr = this.unit_kerja;
    arr.forEach((elem) => {
      elem.ruangan.forEach((el) => {
        if (el.ruangan_level == 1) {
          this.INSOptions.push({
            "ruangan_id": el.ruangan_id,
            "nama_ruangan": el.nama_ruangan,
          });
        }
      });
    });
  },
  methods: {
    async deleteUnitKerja() {
      if (this.onDeleteUnitKerja) {
        const response = await axios.delete(`/unit-kerja/${this.onDeleteUnitKerja.unit_kerja_id}`)
        this.modalHapusUnitKerjaOpen = false;
        Swal.fire({
          icon: "success",
          title: "Berhasil",
          text: "Data berhasil dihapus",
          confirmButtonText: "Ok",
        });
        this.refreshData();
      }
    },
    async deleteInstalasi() {
      if (this.onDeleteInstalasi) {
        const response = await axios.delete(`/unit-kerja/instalasi/${this.onDeleteInstalasi.ruangan_id}`)
        this.modalHapusInstalasiOpen = false;
        Swal.fire({
          icon: "success",
          title: "Berhasil",
          text: "Data berhasil dihapus",
          confirmButtonText: "Ok",
        });
        this.refreshData();
      }
    },
    async deleteRuangan() {
      if (this.onDeleteRuangan) {
        const response = await axios.delete(`/unit-kerja/ruangan/${this.onDeleteRuangan.ruangan_id}`)
        this.modalHapusRuanganOpen = false;
        Swal.fire({
          icon: "success",
          title: "Berhasil",
          text: "Data berhasil dihapus",
          confirmButtonText: "Ok",
        });
        this.refreshData();
      }
    },
    async saveUnitKerja() {
      this.loadingUnitKerja = true;
      try {
        let resp = (await axios.post("unit-kerja", {
          wd_id: this.form.wd_id,
          unit_kerja_nama: this.form.unit_kerja_nama,
          save_unit_kerja: true,
        })).data;
        if (resp.success) {
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil disimpan",
            confirmButtonText: "ok",
          });
        }
        else {
          Swal.fire({
            icon: "error",
            title: "Gagal menyimpan data",
            text: resp.message,
            confirmButtonText: "ok",
          });
        }
        this.refreshData();
        this.errors = {};
        this.form = {};
      }
      catch (err) {
        if (err.response && err.response.status == 422) {
          this.errors = err.response.data.errors;
        }
      }
      this.loadingUnitKerja = false;
    },
    async saveInstalasi() {
      this.loadingInstalasi = true;
      try {
        let resp = (await axios.post("unit-kerja", {
          unit_kerja_id: this.form.unit_kerja_id,
          nama_instalasi: this.form.nama_instalasi,
          save_instalasi: true,
        })).data;
        if (resp.success) {
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil disimpan",
            confirmButtonText: "ok",
          });
        }
        else {
          Swal.fire({
            icon: "error",
            title: "Gagal menyimpan data",
            text: resp.message,
            confirmButtonText: "ok",
          });
        }
        this.refreshData();
        this.errors = {};
        this.form = {};
      }
      catch (err) {
        if (err.response && err.response.status == 422) {
          this.errors = err.response.data.errors;
        }
      }
      this.loadingInstalasi = false;
    },
    async saveRuangan() {
      this.loadingRuangan = true;
      try {
        let resp = (await axios.post("unit-kerja", {
          instalasi_id: this.form.instalasi_id,
          nama_ruangan: this.form.nama_ruangan,
          save_ruangan: true,
        })).data;
        if (resp.success) {
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil disimpan",
            confirmButtonText: "ok",
          });
        }
        else {
          Swal.fire({
            icon: "error",
            title: "Gagal menyimpan data",
            text: resp.message,
            confirmButtonText: "ok",
          });
        }
        this.refreshData();
        this.errors = {};
        this.form = {};
      }
      catch (err) {
        if (err.response && err.response.status == 422) {
          this.errors = err.response.data.errors;
        }
      }
      this.loadingRuangan = false;
    },
    getErrorState(key) {
      if (this.errors[key]) {
        return false
      }
      return null
    },
    getErrorMessage(key) {
      if (this.errors[key]) {
        return this.errors[key].join(', ')
      }
      return null
    },
    getPerPage(count) {
      this.ctx.perPage = count;
    },
    async updateSearch() {
      this.unit_kerja = filterBySearch(this.tmp, this.ctx.filter)
    },
    showHapusModal(item) {
      this.onDeleteItem = item;
      this.modalHapusOpen = true;
    },
    openModalUnitKerjaEdit(unitKerjaData) {
      this.modalUnitOpen = true
      this.unitKerjaData = unitKerjaData
      this.title = 'Ubah'
    },
    openModalInstalasiEdit(instalasiData) {
      this.modalInstalasiOpen = true
      this.instalasiData = instalasiData
      this.title = 'Ubah'
    },
    openModalRuanganEdit(ruanganData) {
      this.modalRuanganOpen = true
      this.ruanganData = ruanganData
      this.title = 'Ubah'
    },
    async refreshData() {
      if (window.tablectxuker) {
        var ctx = window.tablectxuker;
      }
      else {
        var ctx = {
          currentPage: 1,
          perPage: 20,
          filter: "",
          sortBy: "unit_kerja_nama",
          sortDesc: false
        };
      }
      let f1resp = (await axios.get("/unit-kerja?" + buildQueryParams(ctx))).data;
      this.unit_kerja = f1resp.data;
      this.UKEROptions = this.unit_kerja.filter(x => x.unit_kerja_level == 2);
      this.unit_kerja.forEach((elem) => {
        elem.ruangan.forEach((el) => {
          if (el.ruangan_level == 1) {
            this.INSOptions.push({
              "ruangan_id": el.ruangan_id,
              "nama_ruangan": el.nama_ruangan,
            });
          }
        });
      });
    },
  },
  components: { ModalUnitKerja, ModalInstalasi, ModalRuangan }
}
</script>
<style>
.font-size {
  font-size: 1rem;
}
</style>