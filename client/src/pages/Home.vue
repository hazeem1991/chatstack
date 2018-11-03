<template>
  <main-layout>
    <div class="card mt-3">
      <div class="card-body">
        <div class="card-title">
            This Simple chat app Using Vuejs NodeJs Redis Laravel Socketio
        </div>
      </div>
      <div class="card-body">
          More info in the about page
          You enter a user name to log in you shoud add the users in the database manulay
          Have Fun
      </div>
    </div>
  </main-layout>
</template>

<script>
  import MainLayout from '../layouts/Main.vue'

  export default {
    components: {
      MainLayout
    }
  }
</script>
