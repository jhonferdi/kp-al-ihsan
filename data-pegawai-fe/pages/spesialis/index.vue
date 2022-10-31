<template>
	<div>
		<PageHeaderV2 :title="`${$metaInfo.title}`"
			:description="'Daftar nama bidang spesialis di lingkungan rumah sakit Al Ihsan Provinsi Jawa Barat'" :items="[
				{ text: $metaInfo.title, active: true },
			]" :tabs="tabs" :currentTab="currentTab" @currentTab="setCurrentTab" :imageUrl="'spesialis.svg'" />
		<div class="card -mx-content border-top mb-0 p-2" style="border-color:rgba(224, 224, 224, 1) !important">
			<div class="card-body">
				<component :is="currentTab"></component>
			</div>
		</div>
	</div>
</template>
  
<script>
import Spesialis from '~/components/spesialis/Spesialis.vue';
import SubSpesialis from '~/components/spesialis/SubSpesialis.vue';
export default {
	middleware: ["auth"],
	head() {
		return { title: "Spesialis" };
	},
	data: () => ({
		currentTab: "Spesialis",
		tabs: [
			{ componentName: "Spesialis", label: "Spesialis" },
			{ componentName: "SubSpesialis", label: "Sub Spesialis" },
		]
	}),
	methods: {
		setCurrentTab(value) {
			this.currentTab = value;
		}
	},
	components: { Spesialis, SubSpesialis }
}
</script>
<style>
.font-size {
	font-size: 1rem;
}
</style>
