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
                    <h3 class="card-title">Изменение данных Сотрудника</h3>
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
                <div class="row">
                  <div class="col-12 col-sm-6 center">
                    <label class="control-label" for="title">Фото</label>
                    <label class="control-label" v-bind:class="{'hidden':isActive,'text-danger':hasError}">{{ errorPhoto }}</label>
                    <div class="cleanPhoto">
                      <button type="button" v-on:click="onFileDelete" class="btn btn-secondary" data-placement="left" title="Удалить фото">
                        <font-awesome-icon icon="times" size="lg"/>
                      </button>
                    </div>
                    <img :src="employee.photo" width="200px" class="rounded mx-auto d-block img-fluid img-thumbnail"/>
                    <sub>(*Ремомендация фото: 200х300px, размер файла не более 1МВ)</sub>
                    <br/><br/>
                    <div class="custom-file">
                      <input ref="photo" type="file" @change="onFileChange" class="custom-file-input" accept="image/*" lang="ru" multiple />
                      <label class="file custom-file-label" for="customFile">Выберите файл</label>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
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
                        <div class="input-group">
                          <select class="form-control" v-model="employee.position_id" @change="onChange">
                            <option disabled value="">Выберите должность</option>
                            <option v-for="pos in positions" v-bind:value="pos.id">
                              {{ pos.id }}. {{ pos.name_position }}
                            </option>
                          </select>
                          <div class="input-group-append">
                            <button type="button" v-on:click="onCleanPosition" class="btn btn-secondary" data-placement="left" title="Очистить поле">
                              <font-awesome-icon icon="times" size="lg"/>
                            </button>
                          </div>
                        </div>
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
                        <div class="input-group">
                          <select class="form-control" v-model="employee.head">
                            <option disabled value="">Выберите начальника</option>
                            <option v-for="h in heads" v-bind:value="h.id">
                              {{ h.table_number }}: {{ h.nameWorker }} / {{ h.name_position }}
                            </option>
                          </select>
                          <div class="input-group-append">
                            <button type="button" v-on:click="onCleanHead" class="btn btn-secondary" data-placement="left" title="Очистить поле">
                              <font-awesome-icon icon="times" size="lg"/>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-12 form-group">
                    <button class="btn btn-success">Изменить</button>
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
    data: function() {
      return {
        employee: {
          head: '',
          photo: '',
          table_number: '',
          surname: '',
          name: '',
          patronymic: '',
          birthday: '',
          position_id: '',
          salary: '',
          reception_date: '',
        },
        photoDel: '',
        positions: [],
        heads: [],
        employeeId: null,
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
        errorPhoto: '',
      };
    },
    components: {
      NavbarAuthComponent
    },
    mounted() {
      let app = this;
      app.employee.head = 0;
      let id = app.$route.params.id;
      app.photoDel = 0;
      app.dataPositions();
      axios.get('/api/employee/data/edit/' + id)
      .then(function (resp) {
        app.employee = resp.data.data;
        app.employee.head = (resp.data.head)?resp.data.head.head_id:0;
        app.heads = resp.data.heads;
      })
      .catch(function () {
        alert("Could not load your employee")
      });
    },
    methods:{
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
        var data = this.positions[this.employee.position_id];
        var app = this;
        this.employee.salary = data.default_salary;
        axios.get('/api/data/heads',{
          params: {
            "level": data.level,
            "table_number": app.employee.table_number,
          }
        })
        .then( function(resp) {
          app.employee.head = 0;
          app.heads = resp.data;
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      saveForm(){
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
        app.errorPhoto = '';

        let id = app.$route.params.id;
        var files = app.$refs.photo.files;
        var data = new FormData();
        data.append('_method', 'PUT');
        data.append('head', app.employee.head);
        data.append('table_number', app.employee.table_number);
        data.append('surname', app.employee.surname);
        data.append('name', app.employee.name);
        data.append('patronymic', app.employee.patronymic);
        data.append('birthday', app.employee.birthday);
        data.append('position_id', app.employee.position_id);
        data.append('salary', app.employee.salary);
        data.append('reception_date', app.employee.reception_date);
        if (files[0]) {
          data.append('photo', files[0]);
        }
        data.append('photoDelete', app.photoDel);

        axios.post('/api/employee/data/update/' + id, data)
          .then(function (resp) {
            if(resp.data.errors){
              if(resp.data.errors.table_number){
                app.isActive = false;
                app.hasError = true;
                app.errorTableNumber = resp.data.errors.table_number[0];
              }
              if(resp.data.errors.photo){
                app.isActive = false;
                app.hasError = true;
                app.errorPhoto = resp.data.errors.photo[0];
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
              if(resp.data.errors.position){
                app.isActive = false;
                app.hasError = true;
                app.errorPosition = resp.data.errors.position[0];
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
            alert("Could not update your employee");
          });
      },
      onFileChange() {
        var files = this.$refs.photo.files;
        var app = this;
        if (files && files[0]) {
          if(files[0].type.match('image.*')){
            if(files[0].size > 1048576){
              alert('The file size is more than 1 MB');
              this.$refs.photo.value = null;
            } else {
              alert('Good image file');

              var reader = new FileReader();

              reader.onload = function (e) {
                app.employee.photo = e.target.result;
                app.photoDel = 0;
              }

              reader.readAsDataURL(files[0]);
            }
          } else {
            alert('Not image');
            this.$refs.photo.value = null;
          }
        }
      },
      onFileDelete(){
        this.employee.photo = '../../../img/example.jpg';
        this.$refs.photo.value = null;
        this.photoDel = 1;
      },
      onCleanHead() {
        this.employee.head = 0;
      },
      onCleanPosition(){
        this.employee.position_id = '';
        this.employee.salary = '00,00';
      }
    }
  }
</script>
