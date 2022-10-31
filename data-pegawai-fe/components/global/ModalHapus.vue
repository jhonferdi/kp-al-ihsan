<template>
  <modal :open="open" size="xs" @close="$emit('close')" :title="`Hapus ${name}`">
    <div class="text-center">
      <img src="@/assets/images/svg/konfirmasi-hapus.svg" class="d-inline-block mx-auto"
        style="width:130px; max-width: 100%;" alt="">
      <p class="mt-3 mx-auto mb-0" style="max-width:200px">Anda yakin ingin menghapus {{name}} yang dipilih?</p>
    </div>
    <template #footer>
      <b-button variant="outline-darkgreen" class="mr-2 px-4" @click="$emit('close')">Batal</b-button>
      <b-button variant="danger" class="px-4" @click="$emit('delete')">Hapus</b-button>
    </template>
  </modal>
</template>

<script>
export default {
  name: "ModalHapus",
  props:['open','name'],
}
</script>
