<template>
	<div>
		<PageHeaderV2 :title="`${$metaInfo.title}`" 
			:description="'Manajemen data jabatan di lingkungan rumah sakit Al Ihsan Provinsi Jawa Barat'" 
			:items="[
				{text: $metaInfo.title, active: true},
			]" 
			:tabs="tabs"
			:currentTab="currentTab"
			@currentTab="setCurrentTab"
			:imageUrl="'jabatan.svg'" 
			/>
			<div class="card -mx-content border-top mb-0 p-2" style="border-color:rgba(224, 224, 224, 1) !important">
				<div class="card-body">
					<component :is="currentTab"></component>
				</div>
			</div>
	</div>
</template>
  
<script>
import Golongan from '~/components/jabatan/Golongan.vue';
import Jabatan from '~/components/jabatan/Jabatan.vue';
import JenisJabatan from '~/components/jabatan/JenisJabatan.vue';
export default {
    middleware: ["auth"],
    head() {
        return { title: "Jabatan" };
    },
    data: () => ({
        currentTab: "Golongan",
        tabs: [
            { componentName: "Golongan", label: "Golongan" },
            { componentName: "Jabatan", label: "Jabatan" },
            { componentName: "JenisJabatan", label: "Jenis Jabatan" },
        ]
    }),
    methods: {
        setCurrentTab(value) {
            this.currentTab = value;
        }
    },
    components: { Golongan, Jabatan, JenisJabatan }
}
</script>
<style>
.font-size {
	font-size: 1rem;
}
</style>