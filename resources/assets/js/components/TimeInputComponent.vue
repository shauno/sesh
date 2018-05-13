<template>
    <div class="col form-inline text-right">
        <label>{{ label }}</label>
        <select class="form-control" v-model="hours" v-on:change="$emit('update:time', time)">
            <option v-for="h in 24" :value="h-1">{{ h - 1}}</option>
        </select>
        :
        <select class="form-control" v-model="minutes" v-on:change="$emit('update:time', time)">
            <option v-for="m in [0, 15, 30, 45]" :value="m">{{ m }}</option>
        </select>
    </div>
</template>

<script>
    export default {
        props: [
            'label'
        ],
        data() {
            return {
                hours: new Date().getHours(),
                minutes: 0,
            }
        },
        computed: {
            time() {
                return this.hours + ':' + this.minutes;
            }
        },
        created() {
            this.$emit('update:time', this.time);
        }
    }
</script>
