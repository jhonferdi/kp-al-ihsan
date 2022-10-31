<template>
  <div v-if="pagesCount > 0" class="border rounded-lg px-2 d-sm-flex align-items-center justify-content-between" style="background:#FAFAFA; border-color: #E0E0E0 !important; min-height: 40px;">
    <div >
      <span class="mr-2">Tampilkan</span> 
      <select v-model="perPage" class="px-2" style="border:none;min-height:40px;">
        <option v-for="value in perPageOptions" :value="value">{{value}}</option>
      </select>
      <span class="mx-2">Entri</span>
      <span>dari total <strong v-text="itemsLength || 0"> </strong></span>
    </div>
    <div class="d-flex align-items-center">
      <div>
        <span>Halaman</span>
        <select v-model="page" class="px-2 mx-2" style="border:none;min-height:40px;">
          <option v-for="pageNumber in pagesCount" :value="pageNumber">{{pageNumber}}</option>
        </select>
        <span class="mr-3">dari {{pagesCount}}</span>
      </div>
      <div class="ml-2 text-nowrap" style="font-size:20px">
        <a class="inline-block border-left px-3 " :class="page !== 1 ? 'text-darkgreen':'text-disabled'" style="min-height:40px" href="#" @click.prevent="goToPage(page-1)">
          <b-icon icon="chevron-left" />
        </a>
        <a class="inline-block border-left px-3" :class="page !== pagesCount ? 'text-darkgreen':'text-disabled'" style="min-height:40px" href="#" @click.prevent="goToPage(page+1)">
          <b-icon icon="chevron-right" />
        </a>
      </div>
    </div>
  </div>
</template>
<script>
export default{
  name: "TablePagination",
  props: ['items','itemsLength'],
  data(){
    return{
      perPage:20,
      perPageOptions:[5,10,20,50,100],
      page:1,
    }
  },
  methods:{
    goToPage(pageNumber){
      if(pageNumber > 0 && pageNumber <= this.pagesCount) {
        this.page = pageNumber
      }
    },
    getCurrentPage(){
      this.$emit('input', this.page)
    },
    getPerPage(){
      this.$emit('perPage', this.perPage)
    },
  },
  computed:{
    pagesCount(){
      return this.itemsLength ? Math.ceil(this.itemsLength / this.perPage) : 0
    },
  },
  mounted(){
    this.getPerPage()
    this.getCurrentPage()
  },
  watch:{
    perPage(){
      this.page = 1
      this.$emit('perPage', this.perPage)
    },
    page(){
      this.getCurrentPage()
    }
  }
}
</script>
<style scoped>
  .text-disabled{
    color:#BDBDBD
  }
</style>