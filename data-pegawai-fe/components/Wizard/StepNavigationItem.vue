<template>
  <li class="d-flex align-items-center mx-1" style="">
    
    <hr v-if="number > 1" :class="isAvailable ? 'border-darkgreen':'border-lightgray'" class="flex-shrink-0 d-inline-block  mr-2" style="width:60px; max-width:100%; border-width:2px !important;">
    <div v-if="sequential" @click="isAvailable && stepTo(number)" 
      :class="[number < FormWizard.lastVisitedStep || isAvailable ? 'cursor-pointer border border-darkgreen ':'bg-lightgray text-muted cursor-not-allowed', number === currentstep ? 'bg-darkgreen text-white' :'text-darkgreen']" 
        class="w-6 h-6 font-weight-bold text-sm transition d-flex align-items-center justify-content-center rounded-pill " 
        style="width:25px; height:25px; font-size:13px !important; transition: all .3s; border-width:2px !important;">
        <span v-if="isAvailable && number < FormWizard.lastVisitedStep" class="h4 mb-0" style="color:inherit">
          <b-icon   icon="check" style="font-size:20px;" />
        </span>
        <span v-else>{{ number }}</span>
    </div>
    <div v-else @click="stepTo(number)" :class="number === currentstep ? 'bg-darkgreen text-white':'border-darkgreen border text-darkgreen'" 
    class="cursor-pointer w-6 h-6 font-weight-bold text-sm transition d-flex align-items-center justify-content-center rounded-pill "  
    style="width:25px; height:25px; font-size:13px !important; transition: all .3s; border-width:2px !important;">
      <span v-if="isAvailable && number < FormWizard.lastVisitedStep" class="h4 mb-0" style="color:inherit">
        <b-icon   icon="check" style="font-size:20px;" />
      </span>
        <span v-else>{{ number }}</span>
    </div>
  </li>
</template>
<script>
export default {
  props: ['step', 'currentstep','number','steps'],
  inject:['stepTo','FormWizard','sequential'],
  computed:{
    isAvailable(){
      return !this.sequential ? true : this.number <= this.FormWizard.lastVisitedStep ? true : false
    },
    
  }
}
</script>
<style scoped>
  .cursor-pointer{
    cursor: pointer;
  }
  .cursor-not-allowed{
    cursor: not-allowed;
  }
</style>