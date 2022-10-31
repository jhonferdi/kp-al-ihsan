<template>
  <b-card title="Konfigurasi Jam Kerja Pegawai">
    <b-container fluid>
      <b-row class="my-1 mt-3">
        <b-col sm="12">
          <div class="form-check mb-3">
            <input class="form-check-input" type="radio" name="type" id="typeRadios1" value="nip" v-model="form.type">
            <label class="form-check-label" for="typeRadios1">
              Berdasarkan NIP
            </label>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="radio" name="type" id="typeRadios2" value="satuan_kerja" v-model="form.type">
            <label class="form-check-label" for="typeRadios2">
              Berdasarkan Satuan Kerja
            </label>
          </div>
        </b-col>
      </b-row>
      <b-row class="my-1" v-if="form.type == 'nip'">
        <b-col sm="12">
          <label>NIP</label>
        </b-col>
        <b-col sm="6">
          <input type="text" class="form-control" v-model="form.nip">
        </b-col>
      </b-row>
      <b-row class="my-1" v-if="form.type == 'nip'">
        <b-col sm="12">
          <label>Nama Pegawai</label>
        </b-col>
        <b-col sm="6">
          <input type="text" class="form-control" v-model="pegawai.peg_nama" disabled>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Satuan Kerja</label>
        </b-col>
        <b-col sm="6">
          <select
            class="form-control"
            v-model="form.satuan_kerja_id"
            :disabled="form.type == 'nip'"
          >
            <option value="">Pilih satuan kerja</option>
            <option :value="item.satuan_kerja_id" v-for="(item,id) in satuanKerjaList" :key="id">{{ item.satuan_kerja_nama }}</option>
          </select>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Unit Kerja</label>
        </b-col>
        <b-col sm="6">
          <select
            class="form-control"
            v-model="form.unit_kerja_id"
            :disabled="form.type == 'nip'"
          >
            <option value="">Semua unit kerja</option>
            <option :value="item.unit_kerja_id" v-for="(item,id) in unitKerjaList" :key="id">{{ item.unit_kerja_nama }}</option>
          </select>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Jam Kerja</label>
        </b-col>
        <b-col sm="6">
          <select
            class="form-control"
            v-model="form.working_hour_id"
          >
            <option value="">Pilih jam kerja</option>
            <option :value="item.id" v-for="(item,id) in jamKerjaList" :key="id">{{ item.name }}</option>
          </select>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Status</label>
        </b-col>
        <b-col sm="6">
          <b-form-checkbox v-model="form.status" name="check-button" switch></b-form-checkbox>
        </b-col>
      </b-row>
    </b-container>
    
    <div class="mt-3">
      <b-button variant="info" @click="save()"><i class="fa fa-save"></i> Simpan</b-button>
      <nuxt-link :to="'/konfigurasi-jam-kerja-pegawai/'">
        <b-button variant="light">Batal</b-button>
      </nuxt-link>
    </div>
  </b-card>
</template>

<style scoped>
.label-title label{
  font-weight: bold;
  border-bottom: 1px solid #999;
  display: block;
  padding-top: 20px;
}
</style>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import UploadFileCard from '@/components/global/uploadFile'
import { mapGetters } from "vuex"
// import DateDropdown from 'vue-date-dropdown'
import DateDropdown from '@/components/global/DateDropdown'

export default {
  middleware: 'auth',
  components: {
    UploadFileCard,
    DateDropdown
  },

  head() {
    return { title: this.$t('project') }
  },

  data: () => ({
    form: {
      id: null,
      type: 'nip',
      nip: '',
      peg_nama: '',
      satuan_kerja_id: '',
      unit_kerja_id: '',
      working_hour_id: '',
      selected_unit_kerja_id: '',
      status: true
    },
    disabled: 0,
    readonly: false,
    unitKerjaList: [],
    pegawai: {
      satuan_kerja_id: '',
      unit_kerja_id: '',
      peg_nama: ''
    }
  }),
  computed: mapGetters({
    user: "auth/user",
  }),
  async asyncData({ app }) {
    let satuanKerjaList = [];
    let jamKerjaList = [];
    try{
      let f1 = await axios.get("/form-data/satuan-kerja-list");
      satuanKerjaList = f1.data.data;

      // let f2 = await axios.get("/master-jam-kerja/all");
      // jamKerjaList = f2.data.data;
    } catch (error) {
      console.log(error.response);
    }
    
    return {
      satuanKerjaList,
      jamKerjaList
    };
  },
  mounted: function() {
    this.getViewData();
  },
  watch: {
    'form.satuan_kerja_id': {
      handler(val){
        this.getUnitkerja();
        this.getJamKerjaDefault('satuan_kerja');
        this.getJamkerja();
      },
    },
    'form.unit_kerja_id': {
      handler(val){
        this.getJamKerjaDefault('unit_kerja');
      },
    },
    'form.nip': {
      handler(val){
        this.getPegawai();
      },
    }
  },
  methods: {
    isSatuanKerjaDisabled(){
      if(this.user.satuan_kerja_id){
        this.form.satuan_kerja_id = this.user.satuan_kerja_id;
        return true;
      }
      return false;
    },
    isUnitKerjaDisabled(){
      if(this.user.unit_kerja_id){
        this.form.unit_kerja_id = this.user.unit_kerja_id;
        return true;
      }
      return false;
    },
    async getViewData(){
      if(this.$route.name == 'update_master_jam_kerja'){
        try{
          let id = this.$route.params.id;
          let f1 = await axios.get("/konfigurasi-jam-kerja-pegawai/view/" + id);
          let data = f1.data.data;
          this.form = data;
        } catch (error) {
          console.log(error.response);
        }
      }
    },
    async getUnitkerja(){
      this.unitKerjaList = [];
      this.form.unit_kerja_id = this.form.selected_unit_kerja_id;
      if(this.form.satuan_kerja_id){
        try{
          let f1 = await axios.get("/form-data/unit-kerja-list/" + this.form.satuan_kerja_id + '?has_pegawai=1');
          let data = f1.data.data;
          console.log(data);
          this.unitKerjaList = data;
        } catch (error) {
          console.log(error.response);
        }
      }
    },
    async getJamkerja(){
      this.jamKerjaList = [];
      try{
        let f1 = await axios.get("/form-data/jam-kerja-list/" + (this.form.satuan_kerja_id ? this.form.satuan_kerja_id : 0));
        let data = f1.data.data;
        console.log(data);
        this.jamKerjaList = data;
      } catch (error) {
        console.log(error.response);
      }
    },
    async getPegawai(){
      this.pegawai = {
        satuan_kerja_id: '',
        unit_kerja_id: ''
      };

      if(this.form.nip){
        if(this.form.nip.length == 18){
          this.form.satuan_kerja_id = '';
          this.form.unit_kerja_id = '';
          this.form.selected_unit_kerja_id = '';
          try{
            let f1 = await axios.get("/form-data/pegawai/" + this.form.nip);
            let data = f1.data.data;
            console.log(data);
            if(data){
              this.pegawai = data;
              this.form.satuan_kerja_id = data.satuan_kerja_id;
              this.form.unit_kerja_id = data.unit_kerja_id;
              this.form.selected_unit_kerja_id = data.unit_kerja_id;
            }
          } catch (error) {
            console.log(error.response);
          }
        }
      }
    },
    async getJamKerjaDefault(type){
      this.form.working_hour_id = '';
      try{
        if(type == 'satuan_kerja' && this.form.satuan_kerja_id){
          let f1 = await axios.get("/konfigurasi-jam-kerja-default/satuan-kerja/" + this.form.satuan_kerja_id);
          let data = f1.data.data;
          console.log(data);
          if(data){
            this.form.working_hour_id = data.working_hour_id;
          }
        } else if(type == 'unit_kerja' && this.form.unit_kerja_id){
          let f1 = await axios.get("/konfigurasi-jam-kerja-default/unit-kerja/" + this.form.unit_kerja_id);
          let data = f1.data.data;
          console.log(data);
          if(data){
            this.form.working_hour_id = data.working_hour_id;
          }
        }
      } catch (error) {
        console.log(error.response);
      }
    },
    validateForm() {
      if(this.form.type == 'nip'){
        if (!this.form.nip || this.form.nip == '') {
          Swal.fire({
            icon: 'warning',
            title: 'Gagal',
            text: 'Kolom NIP belum diisi',
            timer:5000
          })
          return false
        }
      }
      
      if (!this.form.satuan_kerja_id || this.form.satuan_kerja_id == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Satuan Kerja belum diisi',
          timer:5000
        })
        return false
      }
      if (!this.form.working_hour_id || this.form.working_hour_id == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Jam Kerja belum diisi',
          timer:5000
        })
        return false
      }
      
      return true
    },
    async save() {
      if (!this.validateForm()) return
      this.isLoading = true
      Swal.fire({
        title: 'Menyimpan Data!',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading()
        }
      }).then((result) => {
        /* Read more about handling dismissals below */
      });

      let url = "/konfigurasi-jam-kerja-pegawai/store";
      if(this.$route.name == 'update_master_jam_kerja'){
        url = "/konfigurasi-jam-kerja-pegawai/update";
      }
      
      await axios.post(url, {
          id: this.form.id,
          type: this.form.type,
          nip: this.form.nip,
          satuan_kerja_id: this.form.satuan_kerja_id,
          unit_kerja_id: this.form.unit_kerja_id,
          working_hour_id: this.form.working_hour_id,
          status: this.form.status
        })
        .then(({ data }) => {
          console.log(data);
          if (data.success) {
            Swal.close()
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: 'Data Telah Tersimpan',
              confirmButtonText: 'Ok',
              timer: 5000
            })
            this.$router.push("/konfigurasi-jam-kerja-pegawai")
          } else{
            Swal.close()
            Swal.fire({
              icon: 'warning',
              title: 'Gagal',
              text: data.message,
              confirmButtonText: 'Ok',
              timer: 5000
            })
          }

        })
        .catch((e) => {
          Swal.close()
          this.isLoading = false
          // this.$swal.fire("Gagal", "Gagal Menyimpan Data !", "error");
          Swal.fire({
            icon: 'warning',
            title: 'Gagal',
            text: 'Gagal Menyimpan Data! '+e,
            confirmButtonText: 'Ok'
          })
        });
    },
  },

}
</script>
