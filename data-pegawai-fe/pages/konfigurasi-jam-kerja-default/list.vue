<template>
  <div>
    <b-card title="Konfigurasi Jam Kerja Default">
      <div class="form-group row">
        <div class="col-sm-12 text-right">
          <nuxt-link :to="'/konfigurasi-jam-kerja-default/create'">
            <button class="btn btn-success btn-sm"><i class="fas fa-plus" style="color: #fff" /> Tambah Konfigurasi</button>
          </nuxt-link>
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
            <b-th>Nomor STR</b-th>
            <b-th>Tanggal Kadaluarsa</b-th>
            <b-th>Status</b-th>
            <b-th>Aksi</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <template v-for="(item, i) in str">
            <b-tr :key="i">
              <b-td>{{i+1}}</b-td>
              <b-td>{{ item.nomor_str }}</b-td>
              <b-td>{{ item.tanggal_kadaluarsa }}</b-td>
              <b-td>{{ item.status }}</b-td>
              <b-td>
                <nuxt-link :to="'/str/update/'+item.id">
                  <b-button variant="warning" size="sm">
                    <b-icon icon="pencil"></b-icon>
                  </b-button>
                </nuxt-link>
                <b-button variant="danger" size="sm" @click="deleteItem(item, i)">
                  <b-icon icon="trash"></b-icon>
                </b-button>
              </b-td>
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
    
</div>

</template>

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
    satuan_kerja_id: ''
  }),
  watchQuery: ["params"],
  async asyncData({ app, params, store }) {
    let searchparams = parseParams(params.params);
    let paginate = 20;
    searchparams.perpage = paginate;
    let datalist = [];
    let datacount = 0;
    let current = searchparams.page ? parseInt(searchparams.page) : 1;
    
    let satuanKerjaList = [];
    try{
      let f1 = await axios.get("/str");
      satuanKerjaList = f1.data.data;
    } catch (error) {
      console.log(error.response);
    }
    console.log(searchparams);
    
    return {
      searchparams,
      current,
      datalist,
      datacount,
      paginate,
      paginates: Math.ceil(datacount / paginate),
      satuanKerjaList
    };
  },
  mounted() {
    this.loadData();
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
      let f1 = await axios.get("/str", {
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
      return { path: "/str/" + stringifyParams(searchparams) };
    },
    sortLink(sort) {
      let searchparams = JSON.parse(JSON.stringify(this.searchparams));
      searchparams.sort = sort;
      delete searchparams.page;
      return { path: "/str/" + stringifyParams(searchparams) };
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
        path: "/konfigurasi-jam-kerja-default/" + stringifyParams(searchparams),
      });
    },
    setFilter(col, val) {
      let searchparams = JSON.parse(JSON.stringify(this.searchparams));
      searchparams[col] = val;
      delete searchparams.page;
      this.$router.replace({
        path: "/konfigurasi-jam-kerja-default/" + stringifyParams(searchparams),
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
        text: "Menghapus " + data.unit_kerja.unit_kerja_nama,
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
      await axios.post("/konfigurasi-jam-kerja-default/delete", {
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
