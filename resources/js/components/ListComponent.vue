<template>
<div>
  <div>
    <p><h2>Список сотрудников</h2></p>
  </div>
  <div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Ф.И.О.</th>
            <th>Должность</th>
            <th>Ф.И.О. начальника</th>
            <th>Дата рождения</th>
            <th>Дата приема на работу</th>
            <th>Размер заработной платы</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(worker, index) in workers">
            <td>{{ worker.table_number }}</td>
            <td>{{ worker.nameWorker }}</td>
            <td>{{ worker.name_position }}</td>
            <td>{{ worker.nameHead }}</td>
            <td>{{ worker.birthday }}</td>
            <td>{{ worker.reception_date }}</td>
            <td>{{ worker.salary }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</template>

<script>
  export default {
    data: function () {
      return {
        workers: []
      }
    },
    mounted() {
      var app = this;
      axios.get('/api/list/data/index')
        .then(function (resp) {
          console.log(resp);
          app.workers = resp.data;
        })
        .catch(function (resp) {
          console.log(resp);
          alert("Could not load workers");
        });
      console.log('Component workers.')
    }
  }
</script>

<style>
</style>
