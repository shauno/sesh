<template>
    <div>
        <div class="alert alert-primary" v-show="flash_message">
            {{ flash_message }}
        </div>

        <p><router-link :to="{name: 'create-surf'}" class="btn btn-primary btn-lg">Log a Surf &raquo;</router-link></p>

        <matches-component></matches-component>
    </div>
</template>

<script>
    Vue.component('matches-component', require('./MatchesComponent'));

    export default {
        props: ['flash_message']
    }
</script>