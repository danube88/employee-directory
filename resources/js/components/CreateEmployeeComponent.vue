<template>
  <div>
    <navbarAuth-component></navbarAuth-component>
    <br/>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-xs-12 col-md-12 text-center">
                    <h3 class="card-title">Добавление нового Сотрудника</h3>
                  </div>
                </div>
              </div>
              <label class="control-label" v-bind:class="{'hidden':isActive,'text-danger':hasError}">{{ errorTableNumber }}</label>
              <br/>
              <div class="form-group row">
                <label class="col-sm-7 col-form-label">
                  <h5 class="card-title">Карточка сотрудника №</h5><sub>(6 цифр)</sub>
                </label>
                <div class="col-sm-5">
                  <input type="number" v-model="employee.table_number" class="form-control" value=""/>
                </div>
              </div>
            </div>
            <div class="card-body">
              <form v-on:submit="saveForm()">
                <label class="control-label" v-bind:class="{'hidden':isActive,'text-danger':hasError}">{{ errorSurname }}</label>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Фамилия:</label>
                  <div class="col-sm-9">
                    <input type="text" v-model="employee.surname" class="form-control" placeholder="Введите фамилию" />
                  </div>
                </div>
                <label class="control-label" v-bind:class="{'hidden':isActive,'text-danger':hasError}">{{ errorName }}</label>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Имя:</label>
                  <div class="col-sm-9">
                    <input type="text" v-model="employee.name" class="form-control" placeholder="Введите имя" />
                  </div>
                </div>
                <label class="control-label" v-bind:class="{'hidden':isActive,'text-danger':hasError}">{{ errorPatronymic }}</label>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Отчество:</label>
                  <div class="col-sm-9">
                    <input type="text" v-model="employee.patronymic" class="form-control" placeholder="Введите отчество" />
                  </div>
                </div>
                <label class="control-label" v-bind:class="{'hidden':isActive,'text-danger':hasError}">{{ errorBirthday }}</label>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">День рождение:</label>
                  <div class="col-sm-9">
                    <input type="date" v-model="employee.birthday" class="form-control" />
                  </div>
                </div>
                <label class="control-label" v-bind:class="{'hidden':isActive,'text-danger':hasError}">{{ errorReceptionDate }}</label>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Дата приема на работу:</label>
                  <div class="col-sm-9">
                    <input type="date" v-model="employee.reception_date" class="form-control" />
                  </div>
                </div>
                <label class="control-label" v-bind:class="{'hidden':isActive,'text-danger':hasError}">{{ errorPosition }}</label>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Должность:</label>
                  <div class="col-sm-9">
                    <select class="form-control" v-model="employee.position_id" @change="onChange">
                      <option disabled value="">Выберите должность</option>
                      <option v-for="pos in positions" v-bind:value="pos.id">
                        {{ pos.id }}. {{ pos.name_position }}
                      </option>
                    </select>
                  </div>
                </div>
                <label class="control-label" v-bind:class="{'hidden':isActive,'text-danger':hasError}">{{ errorSalary }}</label>
                <div class="row">
                  <div class="form-group col-sm-3">
                    <label class="col-form-label">Размер заработной платы:</label>
                  </div>
                  <div class="form-group col-sm-9">
                    <div class="input-group">
                      <input type="number" v-model="employee.salary" class="form-control" step="0.01" value="0.00" placeholder="00,00" />
                      <div class="input-group-append">
                        <span class="input-group-text">&#8381;</span>
                        <span class="input-group-text">0,00</span>
                      </div>
                    </div>
                  </div>
                </div>
                <label class="control-label" v-bind:class="{'hidden':isActive,'text-danger':hasError}">{{ errorHead }}</label>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Начальник:</label>
                  <div class="col-sm-9">
                    <select class="form-control" v-model="employee.head">
                      <option disabled value="">Выберите начальника</option>
                      <option v-for="h in heads" v-bind:value="h.id">
                        {{ h.table_number }}: {{ h.nameWorker }} / {{ h.name_position }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12 form-group">
                    <button class="btn btn-success">Добавить</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import NavbarAuthComponent from './layout/navbarAuth.vue';
  export default {
    data: function(){
      return {
        employee: {
          head: '',
          table_number: '000000',
          surname: '',
          name: '',
          patronymic: '',
          birthday: '',
          position_id: '',
          salary: '',
          reception_date: '',
        },
        positions: [],
        heads: [],
        isActive: true,
        hasError: false,
        errorSurname: '',
        errorName: '',
        errorPatronymic: '',
        errorBirthday: '',
        errorReceptionDate: '',
        errorSalary: '',
        errorPosition: '',
        errorHead: '',
        errorTableNumber: '',
      }
    },
    components: {
      NavbarAuthComponent
    },
    mounted() {
      this.dataPositions();
    },
    methods : {
      dataPositions(){
        var app = this;
        axios.get('/api/data/positions')
        .then( function(resp) {
          app.positions = resp.data;
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      onChange(){
        var data = this.positions[this.employee.position_id - 1];
        var app = this;
        this.employee.salary = data.default_salary;
        axios.get('/api/data/heads',{
          params: {
            "level": data.level,
            "table_number": app.employee.table_number,
          }
        })
        .then( function(resp) {
          app.employee.head = '';
          app.heads = resp.data;
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      saveForm() {
        event.preventDefault();
        var app = this;
        app.isActive = true;
        app.hasError = false;
        app.errorSurname = '';
        app.errorName = '';
        app.errorPatronymic = '';
        app.errorBirthday = '';
        app.errorReceptionDate = '';
        app.errorSalary = '';
        app.errorPosition = '';
        app.errorHead = '';
        app.errorTableNumber = '';

        var data = app.employee;

        axios.post('/api/employee/data/create', data)
          .then(function (resp) {
            if(resp.data.errors){
              if(resp.data.errors.table_number){
                app.isActive = false;
                app.hasError = true;
                app.errorTableNumber = resp.data.errors.table_number[0];
              }
              if(resp.data.errors.name){
                app.isActive = false;
                app.hasError = true;
                app.errorName = resp.data.errors.name[0];
              }
              if(resp.data.errors.surname){
                app.isActive = false;
                app.hasError = true;
                app.errorSurname = resp.data.errors.surname[0];
              }
              if(resp.data.errors.patronymic){
                app.isActive = false;
                app.hasError = true;
                app.errorPatronymic = resp.data.errors.patronymic[0];
              }
              if(resp.data.errors.salary){
                app.isActive = false;
                app.hasError = true;
                app.errorSalary = resp.data.errors.salary[0];
              }
              if(resp.data.errors.head){
                app.isActive = false;
                app.hasError = true;
                app.errorHead = resp.data.errors.head[0];
              }
              if(resp.data.errors.position_id){
                app.isActive = false;
                app.hasError = true;
                app.errorPosition = resp.data.errors.position_id[0];
              }
              if(resp.data.errors.birthday){
                app.isActive = false;
                app.hasError = true;
                app.errorBirthday = resp.data.errors.birthday[0];
              }
              if(resp.data.errors.reception_date){
                app.isActive = false;
                app.hasError = true;
                app.errorReceptionDate = resp.data.errors.reception_date[0];
              }
            } else {
              alert(resp.data.data);
              app.$router.push({path: '/home'});
            }
          })
          .catch(function (resp) {
            console.log(resp);
            alert("Could not create your employee");
          });
      }
    }
  }
</script>

<style>
  .form-group {
    margin-bottom: 0px;
  }
</style>
