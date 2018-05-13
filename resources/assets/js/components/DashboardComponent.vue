<template>
    <div>
        <div class="alert alert-primary" v-show="flash_message">
            {{ flash_message }}
        </div>

        <p><router-link to="/surf" class="btn btn-primary btn-lg">Log a Surf &raquo;</router-link></p>
    </div>
</template>

<script>
    export default {
        props: ['flash_message'],
    }
</script>