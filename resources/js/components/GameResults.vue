<template>
    <div>
        <h1>{{ clubname }}</h1>
        <h1>{{ score }}</h1>

        <table class="table table-responsive">
            <thead>
            <tr>
                <th>Points</th>
                <th>Left</th>
            </tr>
            </thead>
            <tbody>
            <input-game-result :gameid="gameid" :points="points"/>
            <tr v-for="event in events">
                <td>{{ event.value }}</td>
                <td>{{ event.points }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: ['gameid', 'club', 'clubname'],
        mounted() {
            axios.get('/api/games/' + this.gameid).then(({data}) => {
                this.events = data[this.club + '_events'];
            });
        },
        data() {
            return {
                events: []
            }
        }
    }
</script>
