import HashChange from "https://cdn.skypack.dev/react-hashchange";

class Add extends React.Component{
  render(){
    return (
      <React.Fragment>
        <form action="" id="add-attribute">
		  <input type="hidden" id="queryParameters" value="" />
          <div for="theme">
            <h2>Please, input attribute name(only in English)</h2>
            <input type="text" name="theme" id="theme" />
          </div>
          <button type="submit">Insert new attribute</button>
        </form>
      </React.Fragment>
    );
  }
}
class List extends React.Component{
  componentDidMount(){
	  
  }
  render(){
    return (
      <React.Fragment>
        <section id="attributes-list">
          <div class="list-cont">
            <header>Hotels</header>
            <footer>7 filters</footer>
          </div>
          <div class="list-cont">
            <header>Olive groves</header>
            <footer>8 filters</footer>
          </div>
          <div class="list-cont">
            <header>Night clubs</header>
            <footer>0 filters</footer>
          </div>
        </section>
      </React.Fragment>
    );
  }
}


const HeaderRender = ({ hash }) => {
  switch(hash){
    case "add":
      return(
        <React.Fragment>
          <a href="#list">Back to list</a>
          <a href="">Save attribute</a>
        </React.Fragment>
      );
    break;
    default:  
      return(
        <React.Fragment>
          <a href="#add">New attribute</a>
        </React.Fragment>
      );
    break;
  }
}
document.title = "Data Attributes";
const UIRender = ({ hash }) => {
  switch(hash){
    case "add": document.title = "Add new Attribute"; break;
    default: document.title = "Data Attributes"; break;
  }
}
const UXRender = ({ hash }) => {
  switch(hash){
    case "add": return <Add />; break;
    default: return <List />; break;
  }
}


$('.data-page > header h2').html(document.title);
ReactDOM.render(
    <HashChange
        render={HeaderRender}
    />,
    document.querySelector('.data-page > header nav')
);
ReactDOM.render(
    <HashChange
        onChange={UIRender}
        render={UXRender}
    />,
    document.querySelector('.data-page > main')
);

const jsonQueryConstructor = (query,pm) => {
	$(query).val(pm);

	
}

const addAttribute = (e,t) => {
	let jsonQuery = $('.add-fields > #queryParameters').val();
	var sendProccess = await fetch('/admin/api/dataServices/filters', {
		method: 'POST',
		body: {'svcQuery': jsonQuery}
	});
}

$(document).ready(function(e,t){
	$('.add-attribute > button').click(addAttribute);
});
