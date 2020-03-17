import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import CreateTournament from "../tournaments/Create";

export default class CreateFederation extends Component {

    constructor(props) {
        super(props);

        this.state = {
            teams: 0,
            levels: 1,
            tournaments: [{
                name: '',
                teams: 2,
                groups: 1,
                steps: 1,
                qualifications: []
            }]
        }
    }

    handleChange = event => {

        console.log(event)
        console.log(event.target.id)

        if (event.target.id == 'levels') {
            console.log(event.target.id)
            console.log(event.target.value)
            let tournaments = this.state.tournaments;
            for (let i = 0; i < event.target.value; i++) {
                if (typeof tournaments[i] === 'undefined') {
                    tournaments.push({
                        name: '',
                        teams: 2,
                        groups: 1,
                        steps: 1,
                        qualifications: []
                    })
                }
            }
            console.log(tournaments)
            this.setState({
                [event.target.id]: event.target.value,
                'tournaments': tournaments
            })
        } else if (event.target.id.includes("TournamentStep")) {

            console.log('tournamentSteps')
            let key = 'Steps';
            let keyName = key.toLowerCase();

            let data = this.state.tournaments
            console.log(data[0].name)

            let value = parseInt(event.target.value);
            data[event.target.dataset.key][keyName] = value

            let qualifications = data[event.target.dataset.key].qualifications
            if (value > 1) {
                for (let i = 1; i < value; i++) {
                    if (typeof qualifications[i - 1] === 'undefined') {
                        qualifications[i - 1] = {
                            name: '',
                            teams: 2,
                            groups: 1,
                            steps: 1,
                            qualifications: []
                        }
                    }
                }
            }

            data[event.target.dataset.key].qualifications = qualifications
            this.setState({tournaments: data})

            console.log(this.state.tournaments)

        } else if (event.target.id.includes('tournament')) {

            let key = event.target.id.replace("tournament", "");
            let keyName = key.toLowerCase();

            let data = this.state.tournaments
            console.log(data[0].name)


            if (keyName === 'teams' || keyName === 'groups') {
                data[event.target.dataset.key][keyName] = parseInt(event.target.value)
            } else {
                data[event.target.dataset.key][keyName] = event.target.value
            }

            this.setState({tournaments: data})

        }
    }

    createLeaguesList = () => {
        let container = []

        // Outer loop to create parent
        for (let i = 0; i < this.state.levels; i++) {
            let tKey = 'tournament-' + i;
            container.push(<CreateTournament key={tKey} keyName={i} tournament={this.state.tournaments[i]}
                                             handleChange={this.handleChange}/>)
        }

        return container
    }

    createList = () => {
        let container = []

        // Outer loop to create parent
        for (let i = 1; i < 10; i++) {
            //Create the parent and add the children
            container.push(<option key={i} value={i}>{i}</option>)
        }

        return container
    }

    render() {
        return (
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-md-4">
                        <div className="card">
                            <div className="card-header">{this.state.name} Qualifications</div>

                            <div className="card-body">
                                <div className="form-group">
                                    <label htmlFor="levels">Levels</label>
                                    <select className="form-control" id="levels" value={this.state.levels}
                                            onChange={this.handleChange}>
                                        {this.createList()}
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="col-md-8">
                        <div className="card">
                            <div className="card-header">Create a League System</div>

                            <div className="card-body">

                                {this.createLeaguesList()}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

if (document

    .getElementById(
        'createFederation'
    )) {
    ReactDOM
        .render(
            <CreateFederation/>,
            document
                .getElementById(
                    'createFederation'
                ))
    ;
}
