import React, {Component} from "react";
import { render } from 'react-dom';
class RepLogApp extends Component {
    render() {
        return <h2>Lift Stuff! <span>❤️</span></h2>;
    }
}

render(<RepLogApp />, document.getElementById('lift-stuff-app'));