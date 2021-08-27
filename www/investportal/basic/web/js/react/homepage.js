class StatisticFeed extends React.Component{
	constuctor(props){
		super(props);
		this.service = props.service;
	}
	componentDidMount(){
		
	}
	render(){
		
	}
}

class DataFeed extends React.Component{
	constuctor(props){
		super(props);
		this.service = props.service;
	}
	componentDidMount(){
		
	}
	render(){
		
	}
}
class DataFeedMobile extends React.Component{
	constuctor(props){
		super(props);
		this.service = props.service;
	}
	componentDidMount(){
		
	}
	render(){
		
	}
}
class NewsFeed extends React.Component{
	componentDidMount(){
		
	}
	render(){
		
	}
}
class NewsFeedMobile extends React.Component{
	componentDidMount(){
		
	}
	render(){
		
	}
}
class EventsFeed extends React.Component{
	componentDidMount(){
		
	}
	render(){
		
	}
}
class AnalyticsFeed extends React.Component{
	componentDidMount(){
		
	}
	render(){
		
	}
}
class AnalyticsFeedMobile extends React.Component{
	componentDidMount(){
		
	}
	render(){
		
	}
}
class ReviewsFeed extends React.Component{
	componentDidMount(){
		
	}
	render(){
		
	}
}
class ReviewsFeedMobile extends React.Component{
	componentDidMount(){
		
	}
	render(){
		
	}
}


let hpdbParams = [
	[],
	[]
],
	hpdbParamsMobile = [
	[],
	[]
],
	statisticParams = [
	[],
	[]
],
	feeds = [
	[],
	[]
];

for(let i=0; i < hpdbParams[][1].length; i++){
	const dc = <DataFeed service="{hpdbParams[i][0]}" />,
		  el = $(hpdbParams[i][1]);
		  
	ReactDOM.render(dc, el);
}
for(let i=0; i < hpdbParamsMobile[][1].length; i++){
	const dc = <DataFeedMobile service="{hpdbParamsMobile[i][0]}" />,
		  el = $(hpdbParamsMobile[i][1]);
		  
	ReactDOM.render(dc, el);
}
for(let i=0; i < statisticParams[][1].length; i++){
	const dc = <StatisticFeed service="{statisticParams[i][0]}" />,
		  el = $(statisticParams[i][1]);
		  
	ReactDOM.render(dc, el);
}
for(let i=0; i < feeds[][1].length; i++){
	const dc = feeds[i][0],
		  el = $(feeds[i][1]);
		  
	ReactDOM.render(dc, el);
}


