<template>
  <div>
    <PageHeaderV2 :title="`${$metaInfo.title}`"
      :description="'Halaman untuk menambah dan menghapus user dashboard simpeg'" :items="[
        { text: $metaInfo.title, active: true },
      ]" :imageUrl="'users.svg'" />
    <div class="card -mx-content border-top mb-0 p-2" style="border-color:rgba(224, 224, 224, 1) !important">
      <div class="card-body">
        <div class="d-sm-inline-flex mb-3">
          <b-input-group class="mb-2 mb-sm-0" style="max-width:230px">
            <b-form-input ref="search" @keyup.enter="updateSearch()" :value="ctx.filter" placeholder="Cari user">
            </b-form-input>
            <b-input-group-append>
              <span>
                <b-button variant="darkgreen" @click="updateSearch()">
                  <b-icon icon="search" />
                </b-button>
              </span>
            </b-input-group-append>
          </b-input-group>
                <div class="ml-sm-3 d-inline-block" :style="[{ width: roleOptions.length > 0 ? '250px' : 'auto' }]"
                  style="min-height:36.53px; max-width:100%">
                  <v-select v-if="roleOptions.length > 0" :options="roleOptions" :reduce="item => item.role_id"
                    label="nama_role" placeholder="Semua Role" v-model="ctx.params.role_id"
                    style="width: 100%;">
                  </v-select>
                </div>
          <div class="mx-3 d-none d-sm-block border-left" style="border-color:#E0E0E0 !important"></div>
          <!-- <b-button variant="darkgreen" :to="{ name: 'user.create' }" size="sm" class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah User</b-button> -->
          <b-button variant="darkgreen" @click="openModalTambah()"
            class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah User</b-button>
          <ModalUser :open="modalUserOpen" :user="userData" size="xs" :title="`${title} User`"
            @close="modalUserOpen = false" @onSave="refreshData" />
        </div>
        <b-table class="table-rounded" responsive striped hover head-variant="darkgreen" id="user-table" ref="table"
          :items="itemsProvider" :current-page="ctx.currentPage" :per-page="ctx.perPage" :filter="ctx.filter"
          :busy="isBusy" :sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc" :fields="fields" show-empty
          :empty-text="`Tidak ada user ditemukan`" :empty-filtered-text="`Tidak ada user bernama '${ctx.filter}'`">
          <template #cell(index)="data">
            {{ data.index + ctx.perPage * (ctx.currentPage - 1) + 1 }}
          </template>

          <template #cell(nama)="row">
            <span>{{ row.item.pegawai ? row.item.pegawai.peg_nama_tanpa_gelar : '-' }}</span>
          </template>
          <template #cell(role)="row">
            <span>{{ row.item.role ? row.item.role.nama_role : '-' }}</span>
          </template>
          <template #cell(username)="row">
            <span>{{ row.item.username || '-' }}</span>
          </template>
          <template #cell(email)="row">
            <span>{{ row.item.pegawai ? row.item.pegawai.peg_email : '-' }}</span>
          </template>
          <template #cell(actions)="row">
            <div class="text-nowrap">
              <!-- <nuxt-link :to="{ name: 'user.edit', params: { id: row.item.id }}" class="text-darkblue p-1 d-inline-flex align-items-center bg-softblue rounded">
                <i class="mb-0 bx bx-edit-alt" style="font-size:18px"></i>
              </nuxt-link> -->
              <a href="#" @click.prevent="openModalEdit(row.item)"
                class="text-darkblue p-1 d-inline-flex align-items-center bg-softblue rounded">
                <i class="mb-0 bx bx-edit-alt" style="font-size:18px"></i>
              </a>
              <b-link size="sm" class="text-danger p-1 d-inline-flex align-items-center bg-pink-new rounded"
                @click="onDeleteItem = row.item; modalHapusOpen = true ">
                <i class="mb-0 bx bx-trash" style="font-size:18px"></i>
              </b-link>
              <b-link size="sm" class="text-danger p-1 d-inline-flex align-items-center bg-pink-new rounded"
                style="font-size:18px" @click="onResetPasswordItem = row.item; modalKonfirmResetOpen = true ">
                <b-icon icon="arrow-repeat"></b-icon>
              </b-link>
            </div>
          </template>
        </b-table>
        <modal-hapus name="user" :open="modalHapusOpen" @close="modalHapusOpen = false" @delete="deleteItem" />
        <modal size="xs" title="Reset Password" :open="modalKonfirmResetOpen" @close="modalKonfirmResetOpen = false">
          <div class="text-center">
            <img src="@/assets/images/svg/konfirmasi-hapus.svg" class="d-inline-block mx-auto"
              style="width:130px; max-width: 100%;" alt="">
            <p class="mt-3 mx-auto mb-0" style="max-width:250px">Anda yakin ingin mereset password {{
                onResetPasswordItem
                  ? onResetPasswordItem.username : ''
            }} yang dipilih?</p>
          </div>
          <template #footer>
            <b-button variant="outline-darkgreen" class="mr-2 px-4" @click="modalKonfirmResetOpen = false">Batal
            </b-button>
            <b-button variant="danger" class="text-white px-4" @click="resetPassword()">Reset</b-button>
          </template>
        </modal>
        <TablePagination v-model="ctx.currentPage" :itemsLength="user_count" @perPage="getPerPage" />
        <!-- <b-row>
          <b-col sm="6">
            <b-pagination v-model="ctx.currentPage" :total-rows="bidang_count" :per-page="ctx.perPage"
              aria-controls="bidang-table">
            </b-pagination>
          </b-col>
          <b-col sm="6" class="d-flex justify-content-end">
            <span class="font-size">Total Data : {{ bidang_count }}</span>
          </b-col>
        </b-row> -->
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import ModalUser from '~/components/modals/ModalUser.vue';
import { buildQueryParams } from '~/plugins/utils'

export default {
  //   middleware: ['auth', 'check-permission'],
  middleware: ["auth"],
  head() {
    return { title: "Management User" };
  },
  async asyncData() {
    if (window.tablectxuser) {
      var ctx = window.tablectxuser;
    }
    else {
      var ctx = {
        currentPage: 1,
        perPage: 20,
        filter: "",
        sortBy: "nama",
        sortDesc: false,
        params: {
          role_id: ''
        }
      };
    }
    let dataResp = (await axios.get("user?" + buildQueryParams(ctx))).data;
    let roleResp = (await axios.get("/role")).data;
    let user = dataResp.data;
    let user_count = dataResp.count;
    let roleOptions = roleResp.data;
    return {
      user,
      user_count,
      ctx,
      roleOptions,
    };
  },
  data: () => ({
    fields: [
      { key: "index", label: "No", thClass: 'bg-darkgreen text-white' },
      { key: "nama", label: "Nama Lengkap", thClass: 'bg-darkgreen text-white', sortable: true, sortDirection: "asc" },
      { key: "role", label: "Role", thClass: 'bg-darkgreen text-white', sortable: false, sortDirection: "asc" },
      { key: "username", label: "Username", thClass: 'bg-darkgreen text-white', sortable: false, sortDirection: "asc" },
      { key: "email", label: "Email", thClass: 'bg-darkgreen text-white', sortable: false, sortDirection: "asc" },
      { key: "actions", label: "Aksi", thClass: 'bg-darkgreen text-white' }
    ],
    modals: {
      user: { title: "Create User", isOpen: false },
    },
    isTableInit: false,
    isBusy: false,
    modalUserOpen: false,
    modalHapusOpen: false,
    onDeleteItem: null,
    modalKonfirmResetOpen: false,
    onResetPasswordItem: null,
    userData: null,
    title: null
  }),
  created: function () {
  },
  watch: {
    "ctx.params.role_id"() {
      this.refreshTable();
    }
  },
  methods: {
    async itemsProvider(ctx) {
      if (!this.isTableInit) {
        this.isTableInit = true;
        return this.user;
      }
      ctx.params = this.ctx.params;
      this.isBusy = true;
      try {
        window.tablectxuser = ctx;
        const response = await axios.get("/user?" + buildQueryParams(ctx));
        this.isBusy = false;
        this.user_count = response.data.count;
        return response.data.data;
      }
      catch (error) {
        this.isBusy = false;
        return [];
      }
    },
    async promptDelete(item) {
      Swal.fire({
        title: "Apakah Anda yakin hendak menghapus " + item.username + "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete("/user/" + item.id);
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.$refs.table.refresh();
        }
      });
    },
    async deleteItem() {
      if (this.onDeleteItem) {
        const response = await axios.delete(`user/delete/${this.onDeleteItem.id}`);
        this.modalHapusOpen = false;
        Swal.fire({
          icon: "success",
          title: "Berhasil",
          text: "Data berhasil dihapus",
          confirmButtonText: "Ok",
        });
        this.$refs.table.refresh();
      }
    },
    async resetPassword() {
      if (this.onResetPasswordItem) {
        let response = await axios.put("user/reset-password/" + this.onResetPasswordItem.id);
        this.modalKonfirmResetOpen = false;
        Swal.fire({
          icon: "success",
          title: "Berhasil",
          text: "Password berhasil direset",
          confirmButtonText: "Ok",
        });
        this.refreshTable();
      }
    },
    refreshTable() {
      this.$refs.table.refresh();
    },
    getPerPage(count) {
      this.ctx.perPage = count;
    },
    updateSearch() {
      this.ctx.filter = this.$refs.search.$el.value;
      this.ctx.currentPage = 1;
    },
    showHapusModal(item) {
      this.onDeleteItem = item;
      this.modalHapusOpen = true;
    },
    openModalEdit(userData) {
      this.modalUserOpen = true
      this.userData = userData
      this.title = 'Ubah'
    },
    openModalTambah() {
      this.modalUserOpen = true
      this.userData = null
      this.title = 'Tambah'
    },
    async refreshData() {
      this.refreshTable()
    },
  },
  components: { ModalUser }
}
</script>
<style>
.font-size {
  font-size: 1rem;
}
</style>
