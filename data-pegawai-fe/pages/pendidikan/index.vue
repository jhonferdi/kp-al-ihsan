<template>
	<div>
		<PageHeaderV2 :title="`${$metaInfo.title}`" 
			:description="'Daftar gelar pendidikan di lingkungan rumah sakit Al Ihsan Provinsi Jawa Barat'" 
			:items="[
				{text: $metaInfo.title, active: true},
			]" 
			:tabs="tabs"
			:currentTab="currentTab"
			@currentTab="setCurrentTab"
			:imageUrl="'pendidikan.svg'" 
			/>
			<div class="card -mx-content border-top mb-0 p-2" style="border-color:rgba(224, 224, 224, 1) !important">
				<div class="card-body">
					<component :is="currentTab"></component>
				</div>
			</div>
	</div>
</template>
  
<script>
import GelarPendidikan from '~/components/pendidikan/GelarPendidikan.vue';
import KualifikasiPendidikan from '~/components/pendidikan/KualifikasiPendidikan.vue';
export default {
    middleware: ["auth"],
    head() {
        return { title: "Pendidikan" };
    },
    data: () => ({
        currentTab: "GelarPendidikan",
        tabs: [
            { componentName: "GelarPendidikan", label: "Gelar Pendidikan" },
            { componentName: "KualifikasiPendidikan", label: "Kualifikasi Pendidikan" },
        ]
    }),
    methods: {
        setCurrentTab(value) {
            this.currentTab = value;
        }
    },
    components: { GelarPendidikan, KualifikasiPendidikan }
}
</script>
<style>
.font-size {
	font-size: 1rem;
}
</style>
