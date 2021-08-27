class TopFeed extends React.Component{
	constuctor(props){
		super(props);
		this.period = props.period;
	}
	componentDidMount(){
		
	}
	render(){
		
	}
}
class LightFeed extends React.Component{
	constuctor(props){
		super(props);
		this.category = props.category;
	}
	componentDidMount(){
		
	}
	render(){
		
	}
}
class DownFeed extends React.Component{
	constuctor(props){
		super(props);
		this.parameter = props.parametr;
	}
	componentDidMount(){
		
	}
	render(){
		
	}
}

class RealtedNews extends React.Component{
	constuctor(props){
		super(props);
		this.contentVersion = props.contentVersion;
	}
	componentDidMount(){
		
	}
	render(){
		
	}
}

let feedParams = [
	[],
	[]
],
	feedMetrics = [
	[],
	[]
],
	realtedsData = [
	[],
	[]
];

for(let i=0; i < feedParams[][1].length; i++){
	const dc = feedParams[i][0],
		  el = $(feedParams[i][1]);
		  
	ReactDOM.render(dc, el);
}
for(let i=0; i < feedMetrics[][1].length; i++){
	const dc = feedMetrics[i][0],
		  el = $(feedMetrics[i][1]);
		  
	ReactDOM.render(dc, el);
}

for(let i=0; i < realtedsData[][1].length; i++){
	const dc = <RealtedNews contentVersion="{realtedsData[i][0]}" />,
		  el = $(realtedsData[i][1]);
		  
	ReactDOM.render(dc, el);
}
