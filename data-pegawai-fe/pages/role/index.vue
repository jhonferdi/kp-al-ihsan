<template>
  <b-card>
    <div class="d-flex justify-content-between mb-1">
      <h2>Roles</h2>
      <div>
        <b-button variant="primary" to="/role/create"
          ><b-icon icon="plus"></b-icon> New role</b-button
        >
      </div>
    </div>
    <b-table-simple hover>
      <b-thead>
        <b-tr>
          <b-th>
            Name
          </b-th>
          <b-th>
            Permissions
          </b-th>
          <b-th>
            Action
          </b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr v-for="role in roles" :key="role.id">
          <b-td>
            {{ role.name }}
          </b-td>
          <b-td>
            <ul>
              <li v-for="permission in role.permissions" :key="role.id + '-' + permission.id">
                {{ permission.name }}
              </li>
            </ul>
          </b-td>
          <b-td>
            <nuxt-link :to="'/role/' + role.id + '/edit'"><b-icon icon="pencil"></b-icon></nuxt-link>
          </b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>
  </b-card>
</template>

<script>
import axios from 'axios'
export default {
  middleware: 'auth',

  head() {
    return { title: "Manage Roles" };
  },
  async asyncData() {
    let f1 = axios.get('role')
    let f1resp = (await f1).data
    let roles = f1resp.data
    return {
      roles,
    }
  },
  data(){
    return{
      text: '',
    }
  }
}
</script>
