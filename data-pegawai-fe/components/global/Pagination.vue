<template>
  <div class="row">
    <div class="col-sm-6" style="line-height: 40px">
      <div class="pagination-summary">
        Entri {{ count > 0 ? ((value - 1) * perpage + 1) : 0 }}
        sd
        {{Math.min(count, value * perpage) }}
        dari total
        {{ count }} entri
        ({{ length }} halaman)
      </div>
    </div>
    <div class="col-sm-6">
      <div class="dataTables_paginate paging_simple_numbers">
        <ul class="pagination">
          <li
            class="paginate_button page-item previous"
            :class="{disabled: value == 1}"
            id="order-table_previous"
          >
            <button @click="setPage(1)" class="page-link">First</button>
          </li>
          <li
            class="paginate_button page-item previous"
            :class="{disabled: value == 1}"
            id="order-table_previous"
          >
            <button @click="setPage(value - 1)" class="page-link">Previous</button>
          </li>
          <li
            class="paginate_button page-item"
            :class="{active: pp == value}"
            v-for="pp in pages"
            :key="pp"
          >
            <button @click="setPage(pp)" class="page-link">
              {{ pp }}
              <span
                v-if="pp != value"
                class="ripple ripple-animate"
                style="height: 33.0324px; width: 33.0324px; animation-duration: 0.7s; animation-timing-function: linear; background: rgb(70, 128, 255); opacity: 0.4; top: -2.73036px; left: -7.61697px;"
              ></span>
            </button>
          </li>
          <li class="paginate_button page-item next" :class="{disabled: (value == length || count <= 0)}">
            <button @click="setPage(value + 1)" class="page-link">Next</button>
          </li>
          <li class="paginate_button page-item next" :class="{disabled: (value == length || count <= 0)}">
            <button @click="setPage(length)" class="page-link">Last</button>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Pagination",
  props: ["value", "length", "count", "perpage"],
  computed: {
    pages() {
      var arr = [];
      var awal = 1;
      var akhir = this.length;

      if (akhir > 5) akhir = 5;

      if (this.value > 3) {
        awal = this.value - 2;
        akhir = this.value + 2;

        if (this.length - this.value == 1 || this.length == this.value) {
          akhir = this.length;
          awal = this.length - 4;

          if (awal == 0) {
            awal = 1;
          }
        }
      }

      var no = 0;
      for (var i = awal; i <= akhir; i++) {
        arr[no] = i;

        no += 1;
      }
      return arr;
    },
  },
  methods: {
    setPage(pp) {
      this.$emit("input", pp);
    },
  },
};
</script>

<style>
div.dataTables_paginate {
  margin: 0;
  white-space: nowrap;
  text-align: right;
}
div.dataTables_paginate ul.pagination {
    margin: 2px 0;
    white-space: nowrap;
    justify-content: flex-end;
}
</style>