<template>
  <!-- start page title -->
  <div class="d-sm-flex justify-content-between">
    <div>
      <b-breadcrumb class="border-none bg-transparent p-0 mb-4">
        <b-breadcrumb-item to="/dashboard">
          Beranda
        </b-breadcrumb-item>
        <b-breadcrumb-item v-for="(val, inx) in items" :key="inx" :active="val.active" :to="val.to"
          :disabled="!$checkPermission('edit-admin')">
          {{ val.text }}
        </b-breadcrumb-item>
      </b-breadcrumb>
      <h1 v-if="title" class="mt-3">{{ title }}</h1>
      <div class="text-muted" style="max-width:400px" v-if="description">
        {{ description }}
      </div>
      <div class="mt-3">
        <ul class="p-0 m-0 d-sm-flex flex-wrap" style="list-style:none">
          <li class="mr-2" v-for="({ componentName, label }, i) in tabs" :key="i">
            <button class="bg-transparent font-bold px-3 py-2 font-weight-bold"
              :class="componentName === currentTab ? 'text-darkgreen' : 'text-muted'"
              style="border:none; border-bottom:1px solid transparent"
              :style="[{ borderColor: componentName === currentTab ? 'rgba(46, 138, 75, 1)' : 'transparent', transition: 'all .3s' }]"
              @click="$emit('currentTab', componentName)">
              {{ label }}
            </button>
            <!-- <b-dropdown  id="dropdown-1" class="font-weight-bold d-flex" :text="'text'" variant="success">
              <template #button-content>
                {{tab.name}} <i style="" class="p-0 m-0 d-inline-block bx bx-caret-down mb-0"></i>
              </template>
              <b-dropdown-item-button @click.prevent="tabIndex = child.id" :active="tabIndex == child.id"
                v-for="(child,i) in tab.children" :key="i"
                v-if="!child.permission || $checkPermission(child.permission)">
                {{child.name}}
              </b-dropdown-item-button>

            </b-dropdown> -->
          </li>
        </ul>
      </div>
    </div>
    <div class="mr-md-5 align-self-end d-flex" v-if="imageUrl">
      <img :src="require(`@/assets/images/breadcrumbs/${imageUrl}`)" style="max-width:250px" alt="">
    </div>
  </div>
</template>
<script>
export default {
  name: 'PageHeaderV2',
  components: {},
  props: {
    title: {
      type: String,
      default: '',
    },
    description: {
      type: String,
      default: '',
    },
    currentTab: {
      type: String,
      default: '',
    },
    items: {
      type: Array,
      default: () => {
        return []
      },
    },
    tabs: {
      type: Array,
      default: () => {
        return []
      },
    },
    imageUrl: {
      type: String,
      default: '',
    },
  },

}
</script>
<style scoped lang="scss">
:deep() {
  .breadcrumb-item {
    font-weight: 500;

    &>a {
      color: #2E8A4B !important;
    }
  }

  .breadcrumb-item+.breadcrumb-item::before {
    color: rgba(224, 224, 224, 1);
    font-size: 15px;
    content: "\F0142";
    font-family: 'Material Design Icons';
  }
}
</style>