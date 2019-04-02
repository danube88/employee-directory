<template>
  <div>
    <div class="form-group">
      <router-link :to="{name: 'createEmployee'}" class="btn btn-success">Create new Employee</router-link>
    </div>
    <vue-bootstrap4-table :rows="rows"
                          :columns="columns"
                          :config="config"
                          @on-change-query="onChangeQuery"
                          :totalRows="total_rows">
      <template slot="paginataion-previous-button">
        Previous
      </template>
      <template slot="paginataion-next-button">
        Next
      </template>
      <template slot="action" slot-scope="props">
        <router-link :to="{name: 'editEmployee', params: {id: props.cell_value}}" class="btn btn-xs btn-primary">
          Edit
        </router-link>
    </template>
    </vue-bootstrap4-table>
  </div>
</template>

<script>
import VueBootstrap4Table from 'vue-bootstrap4-table'
import NavbarComponent from './layout/navbar.vue';
export default {
    components: {
      VueBootstrap4Table,
      NavbarComponent
    },
    data: function() {
        return {
            rows: [],
            columns: [{
                    label: "№",
                    name: "table_number",
                    uniqueId: true,
                    sort: true,
                    initial_sort: true,
                    initial_sort_order: "asc"
                },
                {
                    label: "ФИО",
                    name: "nameWorker",
                    sort: true
                },
                {
                    label: "Должность",
                    name: "name_position",
                    sort: true
                },
                {
                    label: "Начальник",
                    name: "nameHead",
                    sort: true
                },
                {
                    label: "Дата рождения",
                    name: "birthday",
                    sort: true
                },
                {
                    label: "Дата приема на работу",
                    name: "reception_date",
                    sort: true
                },
                {
                    label: "Размер заработной платы",
                    name: "salary",
                    sort: true
                },
                {
                    label: "",
                    name: "id",
                    sort: false,
                    search: false,
                    slot_name: "action"
                }],
            config: {

                card_title: "Список сотрудников",
                server_mode: true,

                checkbox_rows: false,
                rows_selectable: false,
                show_refresh_button: false,
                show_reset_button: false,
                num_of_visibile_pagination_buttons: 7, // default 5
                per_page_options:  [10,  25,  50,  75, 100],
                select_all_checkbox: false,
                global_search: {
                        placeholder: "Введите текст для поиска",
                    },

            },
            queryParams: {
              sort: [],
              filters: [],
              global_search: "",
              per_page: 10,
              page: 1,
            },
            total_rows: 0,
        }
    },
    mounted() {
      this.fetchData();
    },
    methods: {
      onChangeQuery(queryParams) {
        this.queryParams = queryParams;
        this.fetchData();
      },
      fetchData() {
        var self = this;
        axios.get('/api/employee/data/list', {
          params: {
            "queryParams": this.queryParams,
            "page": this.queryParams.page
          }
        })
        .then(function(response) {
          self.rows = response.data.data;
          self.total_rows = response.data.total;
        })
        .catch(function(error) {
          console.log(error);
        });
      }
    }
}
</script>
