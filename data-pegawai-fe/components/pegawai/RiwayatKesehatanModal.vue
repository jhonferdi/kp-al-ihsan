<template>
  <b-modal
    id="modal-riwayat-kesehatan"
    ref="modal-riwayat-kesehatan"
    title="Tambah Riwayat Kesehatan"
  >
    <template #modal-header="{}">
      <h5>Informasi Riwayat Kesehatan</h5>
    </template>
    <template #default="{}" v-if="master_mcu.kategori === 'mcu'">
      <b-row class="my-1">
        <b-col sm="12">
          <label>Penyakit</label>
        </b-col>
        <b-col sm="12">
          <b-form-input
            type="text"
            class="mb-3"
            v-model="master_mcu.nama_penyakit"
            readonly
          ></b-form-input>
        </b-col>
        <b-col sm="12">
          <label>Keterangan</label>
        </b-col>
        <b-col sm="12">
          <b-form-input
            type="text"
            class="mb-3"
            v-model="form_riwayat_kesehatan.keterangan"
            :state="getErrorState('keterangan')"
          ></b-form-input>
          <p style="color: red" v-if="getErrorState('keterangan') === false">
            {{ getErrorMessage("keterangan") }}
          </p>
        </b-col>
      </b-row>
    </template>
    <template #default="{}" v-else-if="master_mcu.kategori === 'keadaan'">
      <b-row class="my-1">
        <b-col sm="12">
          <label>Penyakit</label>
        </b-col>
        <b-col sm="12">
          <b-form-input
            type="text"
            class="mb-3"
            v-model="master_mcu.nama_penyakit"
            readonly
          ></b-form-input>
        </b-col>
        <b-col sm="12">
          <label>Status</label>
        </b-col>
        <b-col sm="12">
          <b-form-select
            type="text"
            class="mb-3"
            v-model="form_riwayat_kesehatan.status"
            :options="SOptions"
            :state="getErrorState('status')"
          >
          </b-form-select>
          <p style="color: red" v-if="getErrorState('status') === false">
            {{ getErrorMessage("status") }}
          </p>
        </b-col>
      </b-row>
    </template>
    <template #modal-footer="{}">
      <!-- Emulate built in modal footer ok and cancel button actions -->
      <b-button
        size="md"
        variant="info"
        @click="saveRiwayatKesehatan()"
        :disabled="loading"
      >
        <i class="fa fa-save"></i> {{ loading ? "Sedang Menyimpan" : "Simpan" }}
      </b-button>
      <b-button size="md" variant="light" @click="cancelRiwayatKesehatan()">
        Batal
      </b-button>
    </template>
  </b-modal>
</template>

<script>
import Swal from "sweetalert2";
import axios from "axios";
import UploadFile from "./UploadFile.vue";

export default {
  props: ["pegawai", "masterDokumenDigitals", "dokumenDigital"],
  components: { UploadFile },
  data() {
    return {
      form_riwayat_kesehatan: null,
      loading: false,
      errors: {},
      master_mcu: {},
      SOptions: [
        { value: "Y", text: "Ya" },
        { value: "T", text: "Tidak" },
      ],
    };
  },
  methods: {
    openRiwayatKesehatanModal(master_mcu, data) {
      this.master_mcu = master_mcu;
      if (data) {
        this.form_riwayat_kesehatan = {
          id: data.id,
          peg_id: data.peg_id,
          keterangan: data.keterangan,
          tahun_mcu: data.tahun_mcu,
          jenis_pemeriksaan: data.jenis_pemeriksaan,
          status: data.status,
          files: {},
          master_mcu_id: data.master_mcu_id,
        };
      } else {
        this.form_riwayat_kesehatan = {
          peg_id: this.pegawai.peg_id,
          files: {},
          master_mcu_id: this.master_mcu.id,
        };
      }
      this.errors = {};
      this.$refs["modal-riwayat-kesehatan"].show();
    },
    async saveRiwayatKesehatan() {
      this.loading = true;
      try {
        if (this.form_riwayat_kesehatan.id) {
          let resp = (
            await axios.patch(
              "pegawai/riwayat-kesehatan/" + this.form_riwayat_kesehatan.id,
              this.form_riwayat_kesehatan
            )
          ).data;
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: resp.message,
            confirmButtonText: "ok",
          });
        } else {
          let resp = (
            await axios.post(
              "pegawai/riwayat-kesehatan",
              this.form_riwayat_kesehatan
            )
          ).data;
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: resp.message,
            confirmButtonText: "ok",
          });
        }
        this.$emit("onSave");
        this.$refs["modal-riwayat-kesehatan"].hide();
      } catch (err) {
        if (err.response && err.response.status == 422) {
          this.errors = err.response.data.errors;
        }
      }
      this.loading = false;
    },
    cancelRiwayatKesehatan() {
      this.$refs["modal-riwayat-kesehatan"].hide();
    },
    getErrorState(key) {
      if (this.errors[key]) {
        return false;
      }
      return null;
    },
    getErrorMessage(key) {
      if (this.errors[key]) {
        return this.errors[key].join(", ");
      }
      return null;
    },
  },
};
</script>

<style></style>
