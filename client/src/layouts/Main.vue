<template>
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <v-link class="nav-link" href="/">Home</v-link>
          </li>
          <li class="nav-item">
            <v-link class="nav-link" href="/chat">Chat</v-link>
          </li>
          <li class="nav-item">
            <v-link class="nav-link" href="/about">About</v-link>
          </li>
        </ul>
      </div>
    </nav>
    <slot></slot>
  </div>
</template>

<script>
  import VLink from '../components/VLink.vue'

  export default {
    components: {
      VLink
    }
  }
</script>

