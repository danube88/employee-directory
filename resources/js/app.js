require('./bootstrap');

window.Vue = require('vue');

import { router } from './router/routes';
import { store } from './vuex/store';

const app = new Vue({
    created(){
      if (this.$store.getters.isAuthenticated) {
        this.$store.dispatch('userRequest');
      }
    },
    router,
    store
  }).$mount('#app')
