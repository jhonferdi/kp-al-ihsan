<script>
export default {
  name: 'PageHeader',
  components: {},
  props: {
    title: {
      type: String,
      default: '',
    },
    items: {
      type: Array,
      default: () => {
        return []
      },
    },
  },
}
</script>

<template>
  <!-- start page title -->
  <div class="row">
    <div class="col-12">
      <div class="page-title-box d-flex align-items-center justify-content-between">
        <h4 class="mb-0 font-size-18">{{ title }}</h4>

        <div class="page-title-right">
          <b-breadcrumb>
            <b-breadcrumb-item v-for="(val, inx) in items" :key="inx" :active="val.active" :to="val.to"
              :disabled="!$checkPermission('edit-admin')">
              {{ val.text }}
            </b-breadcrumb-item>
          </b-breadcrumb>
        </div>
      </div>
    </div>
  </div>
  <!-- end page title -->
</template>
