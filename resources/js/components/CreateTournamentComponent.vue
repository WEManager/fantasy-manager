<template>
    <form>
        <input type="hidden" name="_token" :value="token">
        <div v-if="currentStep === 1">
            <div class="row">
                <div class="col-sm-12">
                    <span>Choose players</span>
                </div>
                <div class="col-sm-4 player" v-for="(player, index) in JSON.parse(players)" :key="index" @click="selectPlayer(player.id)" :class="selectedPlayers.includes(player.id) ? 'selected' : null">
                    {{ player.name }}
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button @click.prevent="currentStep++" type="button" class="btn btn-success">
                        Create Tournament With {{ selectedPlayers.length }} Players
                    </button>
                </div>
            </div>
        </div>

        <div v-if="currentStep === 2">

            <div class="form-group row">
                <label for="competitionType" class="col-md-4 col-form-label text-md-right">Type Of Competition</label>

                <div class="col-md-6">
                    <div class="radio">
                        <label><input type="radio" v-model="competitionType" value="league">League</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" v-model="competitionType" value="groups">Groups</label>
                    </div>
                    <div class="radio disabled">
                        <label><input type="radio" v-model="competitionType" value="playoffs">Play Offs</label>
                    </div>
                </div>
            </div>

            <div class="form-group row" v-if="competitionType === 'groups'">
                <label for="groups" class="col-md-4 col-form-label text-md-right">Amount Of Groups</label>

                <div class="col-md-6">
                    <input id="groups" type="number" min="2" class="form-control" v-model="groups" required>
                </div>
            </div>

            <div class="form-group row" v-if="competitionType !== null && competitionType !== 'playoffs'">
                <div class="col-md-6 offset-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="playoffs" v-model="playOffs">

                        <label class="form-check-label" for="playoffs">
                            Play Offs
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row" v-if="competitionType !== 'playoffs' && playOffs">
                <label for="proceedingToPlayoffs" class="col-md-4 col-form-label text-md-right">Proceeding To
                    PlayOffs</label>

                <div class="col-md-6">
                    <input id="proceedingToPlayoffs" type="number" min="2" :max="selectedPlayers.length" class="form-control" v-model="proceedingToPlayoffs" name="proceedingToPlayoffs" required>
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button @click.prevent="currentStep--">Back</button>
                    <button @click.prevent="currentStep++" type="button" class="btn btn-success">
                        Continue
                    </button>
                </div>
            </div>

        </div>

        <div v-if="currentStep === 3">
            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                <div class="col-md-6">
                    <input id="name" type="text" class="form-control" v-model="name" required autofocus>
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button @click.prevent="currentStep--">Back</button>
                    <button @click.prevent="createTournament" type="submit" class="btn btn-primary">
                        Create Tournament
                    </button>
                </div>
            </div>
        </div>

    </form>
</template>

<script>
    export default {
        props: ['action', 'players', 'token'],
        data() {
            return {
                name: null,
                competitionType: null,
                groups: 2,
                playOffs: false,
                proceedingToPlayoffs: 2,
                selectedPlayers: [],

                currentStep: 1
            }
        },
        methods: {
            selectPlayer(id) {
                if (this.selectedPlayers.includes(id)) {
                    this.selectedPlayers = this.selectedPlayers.filter(e => e !== id);
                } else {
                    this.selectedPlayers.push(id)
                }
            },
            createTournament() {
                axios.post(this.action, {
                    name: this.name,
                    groups: this.groups,
                    playOffs: this.playOffs,
                    competitionType: this.competitionType,
                    selectedPlayers: this.selectedPlayers,
                    proceedingToPlayoffs: this.proceedingToPlayoffs,
                }).then(response => {
                    console.log(response);
                });
            }
        }
    }
</script>
<style>
    .player.selected {
        background: green;
    }
</style>