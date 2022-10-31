<template>
  <div>
    <b-card title="Master GPS">
      <div class="form-group row" v-if="user.role_id == 1">
        <div class="col-sm-12 text-right">
          <button class="btn btn-success btn-sm" @click="openImport()"><i class="fas fa-plus" style="color: #fff" /> Import</button>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-8">

        </div>
        <div class="col-sm-4">
          <div class="row">
            <label class="col-sm-3 col-form-label">
              Cari:
            </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" v-model="searchparams.search" @change="setFilter('search', searchparams.search)">
            </div>
          </div>
        </div>
      </div>
      <b-table-simple responsive>
        <b-thead head-variant="dark">
          <b-tr>
            <b-th>No</b-th>
            <b-th>Satuan Kerja</b-th>
            <b-th>Unit Kerja</b-th>
            <b-th>Nama</b-th>
            <b-th>Latitude</b-th>
            <b-th>Longitude</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <template v-for="(item, i) in datalist">
            <b-tr :key="i">
              <b-td>{{i+1}}</b-td>
              <b-td>{{ item.satuan_kerja ? item.satuan_kerja.satuan_kerja_nama : '-' }}</b-td>
              <b-td>{{ item.unit_kerja ? item.unit_kerja.unit_kerja_nama : '-' }}</b-td>
              <b-td>{{ item.nama }}</b-td>
              <b-td>{{ item.lat }}</b-td>
              <b-td>{{ item.lng }}</b-td>
            </b-tr>
          </template>
        </b-tbody>
      </b-table-simple>
      <div class="col-12">
        <div class="max-width">
          <pagination
            v-model="current"
            :length="paginates"
            :count="datacount"
            :perpage="paginate"
          ></pagination>
        </div>
      </div>
    </b-card>
    
    <div>
      <b-modal id="modal-import" ref="modal-import">
        <template #modal-header="{}">
          <h5>Import GPS</h5>
        </template>
        <template #default="{}">
          <b-row class="my-1">
            <b-col sm="12">
              <label>File GPS (.xlsx)</label>
            </b-col>
            <b-col sm="12">
              <input type="file" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" @change="handleFileUpload($event)">
            </b-col>
          </b-row>
        </template>
        <template #modal-footer="{}">
          <!-- Emulate built in modal footer ok and cancel button actions -->
          <b-button size="md" variant="info" @click="importAction()">
            <i class="fa fa-save"></i> Simpan
          </b-button>
          <b-button size="md" variant="light" @click="cancelImport()">
            Batal
          </b-button>
        </template>
      </b-modal>
    </div>
  </div>

</template>

<style scoped>
.col-form-label{
  text-align: right;
}
</style>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { parseParams, stringifyParams } from "@/utils";
import UploadFileCard from '@/components/global/uploadFile'
import DateDropdown from '@/components/global/DateDropdown'
import { mapGetters } from "vuex"

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
    base_url:process.env.apiUrl.substr(0,(process.env.apiUrl.length)-4),
    isloading: false,
    tempid:'',
    tempindex:'',
    satuan_kerja_id: '',
    file: null
  }),
  watchQuery: ["params"],
  async asyncData({ app, params, store }) {
    let searchparams = parseParams(params.params);
    let paginate = 20;
    searchparams.perpage = paginate;
    let datalist = [];
    let datacount = 0;
    let current = searchparams.page ? parseInt(searchparams.page) : 1;
    
    return {
      searchparams,
      current,
      datalist,
      datacount,
      paginate,
      paginates: Math.ceil(datacount / paginate)
    };
  },
  mounted() {
    this.loadData();
    console.log(this.user);
  },
  computed: {
    ...mapGetters({
      user: "auth/user",
    }),
  },
  filters: {
    formatDesimal: function (value, count) {
      if (!value) return "0";
      if (value == null || value == "") {
        return "-";
      }
      var number = parseFloat(value).toFixed(count);
      if (number == 0.0) {
        return "-";
      }
      return number;
    }
  },
  methods: {
    async loadData(){
      let searchparams = this.searchparams;
      let paginate = 20;
      searchparams.perpage = paginate

      let datalist = [];
      let datacount = 0;
      let f1 = await axios.get("/master-gps", {
        params: {
          searchparams: searchparams,
        },
      });
      let f1resp = (await f1).data;
      datalist = f1resp.data;
      datacount = f1resp.count;
      
      let current = searchparams.page ? parseInt(searchparams.page) : 1;
      let sort = searchparams.sort ? searchparams.sort : "all";
      this.searchparams = searchparams;
      this.current = current;
      this.sort = sort;
      this.datalist = datalist;
      this.datacount = datacount
      this.paginate = paginate;
      this.paginates= Math.ceil(datacount / paginate);
    },
    changePage() {},
    pageLink(page) {
      let searchparams = JSON.parse(JSON.stringify(this.searchparams));
      searchparams.page = page;
      return { path: "/master-gps/" + stringifyParams(searchparams) };
    },
    sortLink(sort) {
      let searchparams = JSON.parse(JSON.stringify(this.searchparams));
      searchparams.sort = sort;
      delete searchparams.page;
      return { path: "/master-gps/" + stringifyParams(searchparams) };
    },
    promptSearch() {
      let search = prompt("Masukkan kata kunci");
      let searchparams = JSON.parse(JSON.stringify(this.searchparams));
      if (search) {
        searchparams.search = search;
      } else {
        delete searchparams.search;
      }
      this.$router.replace({
        path: "/master-gps/" + stringifyParams(searchparams),
      });
    },
    setFilter(col, val) {
      let searchparams = JSON.parse(JSON.stringify(this.searchparams));
      searchparams[col] = val;
      delete searchparams.page;
      this.$router.replace({
        path: "/master-gps/" + stringifyParams(searchparams),
      });
    },
    validateForm(){
      if (!this.no_rekomendasi || this.no_rekomendasi == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom No. Rekomendasi belum diisi',
          timer:5000
        })
        return false
      }
      if (!this.perihal_rekomendasi || this.perihal_rekomendasi == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Perihal Rekomendasi belum diisi',
          timer:5000
        })
        return false
      }
      if (!this.tgl_surat_rekomendasi || this.tgl_surat_rekomendasi == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Tanggal Surat Rekomendasi belum diisi',
          timer:5000
        })
        return false
      }
      return true;
    },
    async deleteItem(data, index){
      console.log(data)
      let confirmation
      await Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Menghapus " + data.name,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#999',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batalkan'
      }).then((result) => {
        confirmation = result.isConfirmed
      });
      if (confirmation) {
      this.isloading = true;
      Swal.fire({
        title: 'Menghapus Data!',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading()
        }
      }).then((result) => {
        /* Read more about handling dismissals below */
      })
      await axios.post("/master-gps/delete", {
            id: data.id,
          })
          .then(({ data }) => {
            // this.$toast.success(data.message, {
            //   icon: "check",
            //   iconPack: "fontawesome",
            //   duration: 5000
            // });
            console.log(data);
            if (data.success) {
              this.isloading = false;
              Swal.close()
              this.loadData();
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data Telah Terhapus',
                confirmButtonText: 'Ok',
                timer: 5000
              })

            } else{
              this.isloading = false;
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
            this.isloading = false;
            Swal.close()
            // this.$swal.fire("Gagal", "Gagal Menyimpan Data !", "error");
            Swal.fire({
              icon: 'warning',
              title: 'Gagal',
              text: 'Gagal Menghapus Data! '+e,
              confirmButtonText: 'Ok'
            })
          });
      }
    },
    async exportpdf(usulan,index){
      console.log(usulan)

      await axios.get("/export/pdf");
    },
    handleFileUpload( event ){
      this.file = event.target.files[0];
      console.log(this.file);
    },
    async importAction(){
      if (!this.file) {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'File belum dipilih',
          timer:5000
        })
        return
      }

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

      let formData = new FormData();
      formData.append('file', this.file);

      let url = "/master-gps/store";
      
      await axios.post(url, 
        formData, 
        {
          headers: {
              'Content-Type': 'multipart/form-data'
          }
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

            this.$refs['modal-import'].hide()
            this.loadData()
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
    openImport(){
      this.$refs['modal-import'].show()
    },
    cancelImport(){
      this.$refs['modal-import'].hide()
    }
  },
  watch: {
    current(newval) {
      this.$router.replace(this.pageLink(newval));
    },
  },
}
</script>
<style scoped>
  .step-done{
      background: #bee690;
  }
  .step-onprogress{
      background: #97b2ee ;
      animation: step-hightlight 1s alternate infinite;
  }
  .step-done h6,
  .step-onprogress h6{
    color: #333 !important;
    }
</style>
