import { Component } from 'react'

export default class CreateTournament extends Component {
  constructor(props) {
    super(props)

    this.state = {
      name: '',
      teams: 2,
      groups: 1,
      steps: 1,
      isStep: false,
    }
  }

  componentDidMount() {
    if (typeof this.props.step !== 'undefined') {
      this.setState({ isStep: true })
    }
  }

  /*handleChange = event => {
        this.setState({
            [event.target.id]: event.target.value,
        })
    };*/

  createOptions = (type) => {
    const container = []

    let limit
    if (type === 'teams') {
      limit = 144
    } else if (type === 'groups') {
      limit = 12
    } else if (type === 'steps') {
      limit = 10
    }

    // Outer loop to create parent
    for (let i = 1; i <= limit; i++) {
      const key = `${i}-${limit}`

      //Create the parent and add the children
      container.push(
        <option key={key} value={i}>
          {i}
        </option>,
      )
    }

    return container
  }

  listSteps = () => {
    if (this.props.step) return []

    const container = []

    for (let i = 1; i < this.props.tournament.steps; i++) {
      const key = `step-${i}`
      container.push(
        <CreateTournament
          key={key}
          keyName={this.props.keyName}
          stepName={i - 1}
          tournament={this.props.tournament.qualifications[i - 1]}
          handleChange={this.props.handleChange}
          step={true}
        />,
      )
    }

    return container
  }

  renderNamingComponent = () => {
    let name = 'League Name'
    let id = 'tournamentName'
    if (this.state.isStep) {
      name = 'Step Name'
      id = 'TournamentStepName'
    }

    return (
      <div className="form-group">
        <label htmlFor={id}>{name}</label>
        <input
          data-key={this.props.keyName}
          type="text"
          className="form-control"
          id={id}
          placeholder="Name..."
          value={this.props.name}
          onChange={this.props.handleChange}
        />
      </div>
    )
  }

  /*renderQualificationComponent = () => {
        return (<div className="form-group">
            <label htmlFor="qualificationPositions">{this.props.qualificationPositions}</label>
            <select className="form-control" id="qualificationPositions" placeholder="Name..." value={this.props.state.name}
                   onChange={this.props.handleChange}/>
        </div>)
    }*/

  renderSaveButton = () => {
    if (this.state.isStep) return null

    return <hr />
  }

  render() {
    return (
      <div className={this.props.step ? 'step' : ''}>
        {this.renderNamingComponent()}
        <div className="form-row">
          <div className="form-group col-sm-4">
            <label htmlFor="teams">Teams</label>
            <select
              data-key={this.props.keyName}
              className="form-control"
              id="tournamentTeams"
              value={this.props.teams}
              onChange={this.props.handleChange}
            >
              {this.createOptions('teams')}
            </select>
          </div>
          <div className="form-group col-sm-4">
            <label htmlFor="groups">Groups</label>
            <select
              data-key={this.props.keyName}
              className="form-control"
              id="tournamentGroups"
              value={this.props.groups}
              onChange={this.props.handleChange}
            >
              {this.createOptions('groups')}
            </select>
          </div>
          <div className="form-group col-sm-4">
            <label htmlFor="steps">Steps</label>
            <select
              data-key={this.props.keyName}
              className="form-control"
              id="tournamentSteps"
              value={this.props.steps}
              onChange={this.props.handleChange}
            >
              {this.createOptions('steps')}
            </select>
          </div>
        </div>
        {this.listSteps()}
        {this.renderSaveButton()}
      </div>
    )
  }
}
