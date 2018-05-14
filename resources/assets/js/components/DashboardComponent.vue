<template>
    <div>
        <div class="alert alert-primary" v-show="flash_message">
            {{ flash_message }}
        </div>

        <p><router-link :to="{name: 'create-surf'}" class="btn btn-primary btn-lg">Log a Surf &raquo;</router-link></p>

        <table class="table table-hover" v-show="this.surfs.length > 0">
            <thead>
            <tr>
                <th scope="col" colspan="3">
                    <h3>Your logged surf sessions</h3>
                </th>
            </tr>
            <tr>
                <th scope="col">Spot</th>
                <th scope="col">Size</th>
                <th scope="col">Wind</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="surf in surfs">
                    <th scope="row">{{ surf.spot_id }}</th>
                    <td>{{ surf.swell_size }} ft</td>
                    <td>{{ surf.wind_speed }} {{ surf.wind_direction }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['flash_message'],
        data() {
            return {
                surfs: []
            }
        },
        created() {
            axios.get("/api/v1/surf")
                .then(response => {
                    this.surfs = response.data;
                });
        }
    }
</script>