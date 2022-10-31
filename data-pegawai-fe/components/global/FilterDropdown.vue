<template>
  <div class="d-inline-block position-relative">
    <div @click="toggleDropdown()" 
      class="d-inline-flex align-items-center border bg-white px-2 py-1 rounded-lg" 
      style="cursor:pointer; border-color: #E0E0E0 !important; transition: all .1s;"
      :style="[{ minWidth: open ? '20px' : '0px'}]"
    >
      <slot name="icon"></slot>
      <span v-if="label">{{`${label}:`}}</span> 
      <strong class="mx-2">{{name || 'Semua'}}</strong> 
      <span class="d-inline-flex align-items-center ml-auto">
        <b-icon icon="x-lg"  v-if="name" @click.stop="resetValue()" class="mr-2 text-darkgreen" style="font-size:13px" />
        <b-icon icon="chevron-down" class=" text-darkgreen" style="font-size:20px" />
      </span>
    </div>
    <div v-if="open && options" 
      style="max-height:200px; overflow:auto; z-index:10; top:100%; left:0; "
      class="position-absolute bg-white border w-100">
      <ul style="list-style:none" class="p-0 m-0">
        <li v-for="(option, i) in options"><a href="#"  :class="value == option.value ? 'bg-light' : ''" class="text-sm d-block  px-3 py-2 text-muted" @click.prevent="setValue(option)">{{option.name}}</a></li>
      </ul>
    </div>
    <div v-if="open" class="position-fixed" style="height:100vh;width:100vw;top:0;left:0" @click="open = false"></div>
  </div>
</template>
<script>
export default{
  name:"FilterDropdown",
  props:['options','label','value',],
  data(){
    return{
      name:null,
      open:false,
    }
  },
  methods: {
    toggleDropdown(){
      this.open = !this.open
    },
    updateValue() {
      this.$emit('input', this.value)
    },
    setValue({name,value}){
      this.name = name
      this.value = value
      this.open = false
    },
    resetValue(){
      this.name = null
      this.$emit('input', null)
    },
    hasValue(){
      return this.options.some((e)=> e.value == this.value)
    },
    getName(){
      if(this.value && this.hasValue()) this.name = this.options.filter((e) => e.value == this.value )[0].name
    }
  },
  mounted(){
    this.getName()
  },
  watch:{
    value(){
      this.getName()
    },
    name(){
      this.updateValue()
    },
  }
}
</script>