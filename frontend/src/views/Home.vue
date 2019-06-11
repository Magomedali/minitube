<template>
  <div class="home">
    <img alt="Vue logo" src="../assets/logo.png">
    <HelloWorld msg="Welcome to Your Vue.js App"/>
  
  <p v-if="name">
    {{name}}  {{version}}
  </p>
  <p v-else>
    Loading ...
  </p>
</div>
</template>

<script>
// @ is an alias to /src
import HelloWorld from '@/components/HelloWorld.vue'
import axios from 'axios'

export default {
    name: 'home',
    data(){
        return {
            name:null,
            version:null
        }
    },
    components: {
        HelloWorld
    },
    mounted() {
        axios.get("/api").then(resp=>{
            this.name = resp.data.name;
            this.version = resp.data.version;
        });
    }
}
</script>
