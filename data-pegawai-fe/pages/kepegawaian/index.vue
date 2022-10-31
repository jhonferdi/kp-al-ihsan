<template>
  <div>
    <PageHeaderV2 :title="`${$metaInfo.title}`"
      :description="'Manajemen kepegawaian di lingkungan rumah sakit Al Ihsan Provinsi Jawa Barat'" :items="[
        { text: $metaInfo.title, active: true },
      ]" :tabs="tabs" :currentTab="currentTab" @currentTab="setCurrentTab" :imageUrl="'kepegawaian.svg'" />
    <div class="card -mx-content border-top mb-0 p-2" style="border-color:rgba(224, 224, 224, 1) !important">
      <div class="card-body">
        <component :is="currentTab"></component>
      </div>
    </div>
  </div>
</template>
  
<script>
import DaftarPegawai from '~/components/kepegawaian/DaftarPegawai.vue';
import PegawaiRuangan from '~/components/kepegawaian/PegawaiRuangan.vue';
import StatusKepegawaian from '~/components/kepegawaian/StatusKepegawaian.vue';
import TenagaKerja from '~/components/kepegawaian/TenagaKerja.vue';
export default {
  middleware: ["auth"],
  head() {
    return { title: "Kepegawaian" };
  },
  data: () => ({
    currentTab: "DaftarPegawai",
    tabs: [
      { componentName: "DaftarPegawai", label: "Daftar Pegawai" },
      { componentName: "PegawaiRuangan", label: "Pegawai Ruangan" },
      { componentName: "StatusKepegawaian", label: "Status Kepegawaian" },
      { componentName: "TenagaKerja", label: "Tenaga Kerja" },
    ]
  }),
  methods: {
    setCurrentTab(value) {
      this.currentTab = value;
    }
  },
  components: { DaftarPegawai, PegawaiRuangan, StatusKepegawaian, TenagaKerja }
}
</script>
<style>
.font-size {
  font-size: 1rem;
}
</style>
