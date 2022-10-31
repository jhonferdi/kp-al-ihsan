<template>
  <b-modal id="view-execution-logs" title="Detail Eksekusi" @hidden="hiddenModal" size="xl" ok-only>
    <pre class="highlight" v-text="contents" ref="contents"></pre>
  </b-modal>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      contents: '',
      job_execution_id: '',
      last_job_execution_log_id: null,
      is_finished: false,
      interval: false,
    }
  },
  methods: {
    async appendLogs() {
      let isScrollBottom = this.isScrollBottom()
      let resp = (await axios.get(`job-execution/${this.job_execution_id}/logs`, {params: {
        last_job_execution_log_id: this.last_job_execution_log_id
      }})).data
      this.contents += "\n" + resp.contents
      if (resp.last_job_execution_log_id) {
        this.last_job_execution_log_id = resp.last_job_execution_log_id
      }
      this.is_finished = resp.is_finished
      setTimeout(() => {
        console.log("trigger scroll bottom")
        if (isScrollBottom) {
          this.moveScrollBottom()
        }
      }, 10)
      if (this.is_finished) {
        clearInterval(this.interval)
      }
    },
    async viewExecutionLogs(job_execution) {
      this.contents = 'Loading...'
      this.$bvModal.show('view-execution-logs')
      this.job_execution_id = job_execution.id
      let resp = (await axios.get(`job-execution/${this.job_execution_id}/logs`)).data
      this.contents = resp.contents
      this.last_job_execution_log_id = resp.last_job_execution_log_id
      this.is_finished = resp.is_finished
      setTimeout(() => {
        this.moveScrollBottom()
      }, 10)
      if (!this.is_finished) {
        this.interval = setInterval(this.appendLogs, 5000)
      }
    },
    hiddenModal() {
      if (this.interval) {
        clearInterval(this.interval)
      }
    },
    isScrollBottom() {
      var el = this.$refs.contents;
      return el.scrollHeight - el.clientHeight <= el.scrollTop + 1;
    },
    moveScrollBottom() {
      var el = this.$refs.contents;
      el.scrollTop = el.scrollHeight - el.clientHeight
    },
  },
  mounted() {
    this.$root.$on('view-execution-logs', this.viewExecutionLogs)
  },
  beforeDestroy() {
    this.$root.$off('view-execution-logs', this.viewExecutionLogs)
    if (this.interval) {
      clearInterval(this.interval)
    }
  }
}
</script>

<style scoped>
.highlight {
  background-color: #f9f9f9;
  box-shadow: 0 1px 1px rgb(0 0 0 / 13%);
  height: 500px;
}
</style>
