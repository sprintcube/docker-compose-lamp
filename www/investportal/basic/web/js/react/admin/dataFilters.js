import HashChange from "https://cdn.skypack.dev/react-hashchange";

class List extends React.Component{
  componentDidMount(){
	  
  }
  render(){
    return (
      <React.Fragment>
        <section id="filters-list">
          <header><h2>Attributes filters list</h2></header>
          <main>
            <div id="filter-card">
              <div id="header">Hotels</div>
              <div id="main">
                <ul>
                  <li>Region data - <span>Smart datasets</span></li>
                  <li>Cost - <span>Price parameters</span></li>
                  <li>Profitability - <span>Percentage value</span></li>
                  <li>Photogallery</li>
                  <li>Numbers count - <span>Integer value</span></li>
                  <li>Availability of SPA - <span>The form of the answer to the question</span></li> 
                 <li>Land area - <span>Integer value</span></li> 
                </ul>
              </div>
              <div id="footer">
                <nav>
                  <span>Edit</span>
                  <span>Delete</span>
                </nav>
              </div>
            </div>
            <div id="filter-card">
              <div id="header">Olive groves</div>
              <div id="main">
                <ul>
                  <li>Region data - <span>Smart datasets</span></li>
                  <li>Granting a residence permit - <span>The form of the answer to the question</span></li> 
                  <li>Cost - <span>Price parameters</span></li>
                  <li>Profitability - <span>Percentage value</span></li>
                  <li>Photogallery</li>
                  <li>Area in hectares - <span>Integer value</span></li>
                  <li>Annual yield in tons - <span>Integer value</span></li> 
                  <li>Personnel - <span>The form of the answer to the question</span></li> 
                </ul>
              </div>
              <div id="footer">
                <nav>
                  <span>Edit</span>
                  <span>Delete</span>
                </nav>
              </div>
            </div>
            <div id="filter-card">
              <div id="header">Night clubs</div>
              <div id="main">
                <ul><li>Not filters</li></ul>
              </div>
              <div id="footer">
                <nav>
                  <span>Add</span>
                  <span>Delete</span>
                </nav>
              </div>
            </div>
          </main>
          
        </section>
      </React.Fragment>
    );
  }
}
class Add extends React.Component{
  render(){
    return (
      <React.Fragment>
        <div class="add-fields">
		  <input type="hidden" id="queryParameters" value="" />
          <header><h2>Add new filters group for attribute</h2></header>
          <main>
            <div>
              <input type="text" name="fieldName" id="fieldName" placeholder="Enter the field name" />
              <select name="fieldType" id="fieldType">
                <option>Select form field type</option>
                <option value="defaultField">Data field</option>
                <option value="intField">Integer field</option>
                <option value="precentableField">Precentable field</option>
                <option value="costField">Cost field</option>
                <option value="smartDatasets">Smart Datasets</option>
                <option value="photogalleryField">Photogallery</option>
              </select>
            </div>
          </main>
          <footer><button>Add field</button></footer></div>
          <ul class="add-modals">
			<li>
				<section class="form-rpa-addon">
				  <header>
					<h2>Smart Datasets Window</h2>
					<div id="header-right">
					  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAAApElEQVRYhe2WUQqAIAxAt+iU3SY6dsH6cSDamEtxBXtfaanP6TSAICigPi4i2jwFzBKr9AIRsWsmjSwzBukSmL4nuOUgAbuEJCCVn+qzwXezxEiB9GyTeCug9HGkqrP8tko1bshpKJVbyFO57IdxT0PxIJIYfUC5R8BdwLwElk0IoC/Z/yLAaDNrjZR7BEKg2gOz/oQY9wios7XmvTrg1y6jILgBISbRIFShha8AAAAASUVORK5CYII=" data-operation="Save" />
					  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAAB40lEQVRYhe2WzU4TURTH/+PWLpWotZGvWN+FUrZin6BQFwRehJ0YHoBUSoJRH4QuGje6EPZ8rEqpPxZzJh3Knem9A0STcpImzT3n/3HPzNx7pMeY9ohCioGKpBVJS5JmJVUs9UfSb0nfJR1GUXR8nyYFlIEdYMDkGAJt4M19ideBcyPvA3vAe6AKPLVfFVi1XN9qz4Hlu4p/tB1hu5rzwMwD+6lutIqK143gCtgogN807DC4E/bMk7YHi4+ZADgDXoYAd5O2FxVPcXWMa8cXULHW9V3PHGgCM471GaDpWF8wrgFQ9jHQMsd7jtya5bppEybetdyaA9fOyrkM/LDi1YxddtMmXGsOXMPy33wM/LTitxn5ccFcccNUrabnY+DCiks5Nc+BI0bRA17k1Jes7mI898RVP9GlO/LulUTn70SW/+ER/POXcN2KH+IzvHVOuAy8Jj40+sC8Ix96EC0SchAZ6LM53vcC5HMdGNenENArRpfR5h3Et4zjNO8zzQIvM7qOg02YeHIdL4XiE5IWo4GkAyx4YBZTbR8C64XEU4Q14vsce5nawAfgHfEJV7L/DeALcJlqe7GdO0w8A7bxG0oHxAOs1wASOpaXJdUl1STN6eZY/kvxWP41iqKTEN7HmO64BtsZlPR175QhAAAAAElFTkSuQmCC" data-operation="Close" />
					</div>
				  </header>
				  <main>
					<div id="smdf-header">
					  <nav>
						<a href="" class="active">Single mode</a>
						<a href="">Search mode</a>
					  </nav>
					</div>
					<div id="smdf-content">
					  <section><header>
						<h3>Select form element:</h3>
						<div><input type="radio" id="element" value="list" />List</div>
						<div><input type="radio" id="element" value="input" />Input field</div>
					  </header>
					  <main>
						<h3>Input your need dataset:</h3>
						<label for="dataset">Add dataset</label>
						<input type="file" id="dataset" accept="application/xml, application/json, application/vnd.ms-excel" />
						<div class="downloaded-file"></div>
					  </main></section>
					  <section class="mode-hidden"><header>
						<h3>Select form elements:</h3>
						<div><input type="radio" id="element" value="list" />List's</div>
						<div><input type="radio" id="element" value="input" />Input fields</div>
					  </header>
					  <main>
						 <h3>Input your priority dataset:</h3>
						<label for="dataset">Add dataset</label>
						<input type="file" id="dataset" accept="application/xml, application/json, application/vnd.ms-excel" />
						<div class="downloaded-file"></div>
					  </main>
					  <footer>
						 <h3>Add datasets in search group:</h3>
						<label for="datasets">Add datasets</label>
						<input type="file" id="datasets" accept="application/xml, application/json, application/vnd.ms-excel" multiple/>
						<div class="downloaded-files"><ul></ul></div>
					  </footer></section>
					</div>
				  </main>
				</section>
			</li>
			<li>
				<section class="form-rpa-addon">
				  <header>
					<h2>Photogallery Window</h2>
					<div id="header-right">
					  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAAApElEQVRYhe2WUQqAIAxAt+iU3SY6dsH6cSDamEtxBXtfaanP6TSAICigPi4i2jwFzBKr9AIRsWsmjSwzBukSmL4nuOUgAbuEJCCVn+qzwXezxEiB9GyTeCug9HGkqrP8tko1bshpKJVbyFO57IdxT0PxIJIYfUC5R8BdwLwElk0IoC/Z/yLAaDNrjZR7BEKg2gOz/oQY9wios7XmvTrg1y6jILgBISbRIFShha8AAAAASUVORK5CYII=" data-operation="Save" />
					  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAAB40lEQVRYhe2WzU4TURTH/+PWLpWotZGvWN+FUrZin6BQFwRehJ0YHoBUSoJRH4QuGje6EPZ8rEqpPxZzJh3Knem9A0STcpImzT3n/3HPzNx7pMeY9ohCioGKpBVJS5JmJVUs9UfSb0nfJR1GUXR8nyYFlIEdYMDkGAJt4M19ideBcyPvA3vAe6AKPLVfFVi1XN9qz4Hlu4p/tB1hu5rzwMwD+6lutIqK143gCtgogN807DC4E/bMk7YHi4+ZADgDXoYAd5O2FxVPcXWMa8cXULHW9V3PHGgCM471GaDpWF8wrgFQ9jHQMsd7jtya5bppEybetdyaA9fOyrkM/LDi1YxddtMmXGsOXMPy33wM/LTitxn5ccFcccNUrabnY+DCiks5Nc+BI0bRA17k1Jes7mI898RVP9GlO/LulUTn70SW/+ER/POXcN2KH+IzvHVOuAy8Jj40+sC8Ix96EC0SchAZ6LM53vcC5HMdGNenENArRpfR5h3Et4zjNO8zzQIvM7qOg02YeHIdL4XiE5IWo4GkAyx4YBZTbR8C64XEU4Q14vsce5nawAfgHfEJV7L/DeALcJlqe7GdO0w8A7bxG0oHxAOs1wASOpaXJdUl1STN6eZY/kvxWP41iqKTEN7HmO64BtsZlPR175QhAAAAAElFTkSuQmCC" data-operation="Close" />
					</div>
				  </header>
				  <main>
					  <ul id="pg">
						<li>
						  <span>Select the image formats in which file downloads will be available in current attribute:</span>
						  <div>
							<input type="checkbox" name="format" id="format" value="jpeg" />
							<span>JPEG</span>
						  </div>
						  <div>
							<input type="checkbox" name="format" id="format" value="png" />
							<span>PNG</span>
						  </div>
						  <div>
							<input type="checkbox" name="format" id="format" value="webp" />
							<span>WEBP</span>
						  </div>
						  <div>
							<input type="checkbox" name="format" id="format" value="vnd.ms-photo" />
							<span>JPEG-XR</span>
						  </div>
						</li>
						<li>
						  <span>Enter the maximum number of uploaded photos in the gallery:</span>
						  <div>
							<input type="number" id="imagesCount" name="imagesCount" value="4" />
						  </div>
						</li>
						
					  </ul>
				  </main>
				</section>
			</li>
          </ul>
      </React.Fragment>
    );
  }
}
class Edit extends React.Component{
  componentDidMount(){
	  
  }
  render(){
    return (
      <React.Fragment>
        <div class="edit-fields">
		  <input type="hidden" id="queryParameters" value="" />
          <header><h2>Add new filters group for attribute</h2></header>
          <main>
            <div>
              <input type="text" name="fieldName" id="fieldName" placeholder="Enter the field name" />
              <select name="fieldType" id="fieldType">
                <option>Select form field type</option>
                <option value="defaultField">Data field</option>
                <option value="intField">Integer field</option>
                <option value="precentableField">Precentable field</option>
                <option value="costField">Cost field</option>
                <option value="smartDatasets">Smart Datasets</option>
                <option value="photogalleryField">Photogallery</option>
              </select>
            </div>
          </main>
          <footer><button>Add field</button></footer></div>
          <ul class="edit-modals">
			<li>
				<section class="form-rpa-addon">
				  <header>
					<h2>Smart Datasets Window</h2>
					<div id="header-right">
					  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAAApElEQVRYhe2WUQqAIAxAt+iU3SY6dsH6cSDamEtxBXtfaanP6TSAICigPi4i2jwFzBKr9AIRsWsmjSwzBukSmL4nuOUgAbuEJCCVn+qzwXezxEiB9GyTeCug9HGkqrP8tko1bshpKJVbyFO57IdxT0PxIJIYfUC5R8BdwLwElk0IoC/Z/yLAaDNrjZR7BEKg2gOz/oQY9wios7XmvTrg1y6jILgBISbRIFShha8AAAAASUVORK5CYII=" data-operation="Save" />
					  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAAB40lEQVRYhe2WzU4TURTH/+PWLpWotZGvWN+FUrZin6BQFwRehJ0YHoBUSoJRH4QuGje6EPZ8rEqpPxZzJh3Knem9A0STcpImzT3n/3HPzNx7pMeY9ohCioGKpBVJS5JmJVUs9UfSb0nfJR1GUXR8nyYFlIEdYMDkGAJt4M19ideBcyPvA3vAe6AKPLVfFVi1XN9qz4Hlu4p/tB1hu5rzwMwD+6lutIqK143gCtgogN807DC4E/bMk7YHi4+ZADgDXoYAd5O2FxVPcXWMa8cXULHW9V3PHGgCM471GaDpWF8wrgFQ9jHQMsd7jtya5bppEybetdyaA9fOyrkM/LDi1YxddtMmXGsOXMPy33wM/LTitxn5ccFcccNUrabnY+DCiks5Nc+BI0bRA17k1Jes7mI898RVP9GlO/LulUTn70SW/+ER/POXcN2KH+IzvHVOuAy8Jj40+sC8Ix96EC0SchAZ6LM53vcC5HMdGNenENArRpfR5h3Et4zjNO8zzQIvM7qOg02YeHIdL4XiE5IWo4GkAyx4YBZTbR8C64XEU4Q14vsce5nawAfgHfEJV7L/DeALcJlqe7GdO0w8A7bxG0oHxAOs1wASOpaXJdUl1STN6eZY/kvxWP41iqKTEN7HmO64BtsZlPR175QhAAAAAElFTkSuQmCC" data-operation="Close" />
					</div>
				  </header>
				  <main>
					<div id="smdf-header">
					  <nav>
						<a href="" class="active">Single mode</a>
						<a href="">Search mode</a>
					  </nav>
					</div>
					<div id="smdf-content">
					  <section><header>
						<h3>Select form element:</h3>
						<div><input type="radio" id="element" value="list" />List</div>
						<div><input type="radio" id="element" value="input" />Input field</div>
					  </header>
					  <main>
						<h3>Update your need dataset:</h3>
						<label for="dataset">Add dataset</label>
						<input type="file" id="dataset" accept="application/xml, application/json, application/vnd.ms-excel" />
						<div class="downloaded-file"></div>
					  </main></section>
					  <section class="mode-hidden"><header>
						<h3>Select form elements:</h3>
						<div><input type="radio" id="element" value="list" />List's</div>
						<div><input type="radio" id="element" value="input" />Input fields</div>
					  </header>
					  <main>
						 <h3>Update your priority dataset:</h3>
						<label for="dataset">Add dataset</label>
						<input type="file" id="dataset" accept="application/xml, application/json, application/vnd.ms-excel" />
						<div class="downloaded-file"></div>
					  </main>
					  <footer>
						 <h3>Update datasets in search group:</h3>
						<label for="datasets">Add datasets</label>
						<input type="file" id="datasets" accept="application/xml, application/json, application/vnd.ms-excel" multiple/>
						<div class="downloaded-files"><ul></ul></div>
					  </footer></section>
					</div>
				  </main>
				</section>
			</li>
			<li>
				<section class="form-rpa-addon">
				  <header>
					<h2>Photogallery Window</h2>
					<div id="header-right">
					  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAAApElEQVRYhe2WUQqAIAxAt+iU3SY6dsH6cSDamEtxBXtfaanP6TSAICigPi4i2jwFzBKr9AIRsWsmjSwzBukSmL4nuOUgAbuEJCCVn+qzwXezxEiB9GyTeCug9HGkqrP8tko1bshpKJVbyFO57IdxT0PxIJIYfUC5R8BdwLwElk0IoC/Z/yLAaDNrjZR7BEKg2gOz/oQY9wios7XmvTrg1y6jILgBISbRIFShha8AAAAASUVORK5CYII=" data-operation="Save" />
					  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAAB40lEQVRYhe2WzU4TURTH/+PWLpWotZGvWN+FUrZin6BQFwRehJ0YHoBUSoJRH4QuGje6EPZ8rEqpPxZzJh3Knem9A0STcpImzT3n/3HPzNx7pMeY9ohCioGKpBVJS5JmJVUs9UfSb0nfJR1GUXR8nyYFlIEdYMDkGAJt4M19ideBcyPvA3vAe6AKPLVfFVi1XN9qz4Hlu4p/tB1hu5rzwMwD+6lutIqK143gCtgogN807DC4E/bMk7YHi4+ZADgDXoYAd5O2FxVPcXWMa8cXULHW9V3PHGgCM471GaDpWF8wrgFQ9jHQMsd7jtya5bppEybetdyaA9fOyrkM/LDi1YxddtMmXGsOXMPy33wM/LTitxn5ccFcccNUrabnY+DCiks5Nc+BI0bRA17k1Jes7mI898RVP9GlO/LulUTn70SW/+ER/POXcN2KH+IzvHVOuAy8Jj40+sC8Ix96EC0SchAZ6LM53vcC5HMdGNenENArRpfR5h3Et4zjNO8zzQIvM7qOg02YeHIdL4XiE5IWo4GkAyx4YBZTbR8C64XEU4Q14vsce5nawAfgHfEJV7L/DeALcJlqe7GdO0w8A7bxG0oHxAOs1wASOpaXJdUl1STN6eZY/kvxWP41iqKTEN7HmO64BtsZlPR175QhAAAAAElFTkSuQmCC" data-operation="Close" />
					</div>
				  </header>
				  <main>
					  <ul id="pg">
						<li>
						  <span>Select the image formats in which file downloads will be available in current attribute:</span>
						  <div>
							<input type="checkbox" name="format" id="format" value="jpeg" />
							<span>JPEG</span>
						  </div>
						  <div>
							<input type="checkbox" name="format" id="format" value="png" />
							<span>PNG</span>
						  </div>
						  <div>
							<input type="checkbox" name="format" id="format" value="webp" />
							<span>WEBP</span>
						  </div>
						  <div>
							<input type="checkbox" name="format" id="format" value="vnd.ms-photo" />
							<span>JPEG-XR</span>
						  </div>
						</li>
						<li>
						  <span>Enter the maximum number of uploaded photos in the gallery:</span>
						  <div>
							<input type="number" id="imagesCount" name="imagesCount" value="4" />
						  </div>
						</li>
						
					  </ul>
				  </main>
				</section>
			</li>
          </ul>
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
          <a href="">Save filters</a>
        </React.Fragment>
      );
    break;
    default:  
      return(
        <React.Fragment>
          <a href="#add">New filter</a>
        </React.Fragment>
      );
    break;
  }
}
document.title = "Data Filters";
const UIRender = ({ hash }) => {
  switch(hash){
    case "add": document.title = "Add new Filter"; break;
    default: document.title = "Data Filters"; break;
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

const AddField = () => {
  $('.add-fields > main').append('<div>\n\t<input type="text" name="fieldName" id="fieldName" placeholder="Enter the field name" />\n\t<select name="fieldType" id="fieldType"><option>Select form field type</option><option value="defaultField">Data field</option><option value="intField">Integer field</option><option value="precentableField">Precentable field</option><option value="costField">Cost field</option><option value="smartDatasets">Smart Datasets</option><option value="photogalleryField">Photogallery</option></select></div>');
}

const AddFieldEvent = () => {
  AddField();
}

const jsonQueryConstructor = (query,pm) => {
	let result = {};
	let currentFilterPage {
		isAdd: document.location.href.indexOf("add") ? true : null,
		isEdit: document.location.href.indexOf("edit") ? true : null
	};
	

	let serviceCmd;


	if(query.defaultParameter.element === 'dataset'){
		let q = query.pms,
			res = {};

		if(isEdit){ serviceCmd = 'editDatasets'; }
		if(isAdd){ serviceCmd = 'sendDatasets'; }

		result.command = { subCMD: serviceCmd };

		switch(query.service){
			case 'add':
				if(q.dataConstructor.group && q.dataConstructor.priority){
					res = q.dataConstructor.priority.concat(q.dataConstructor.group);
				}
				else{
					res = q.dataConstructor.single;
				}
			break;
			case 'edit':
				if(q.dataConstructor.group && q.dataConstructor.priority){
					res = q.dataConstructor.priority.concat(q.dataConstructor.group);
				}
				else{
					res = q.dataConstructor.single;
				}
			break;
		}

		result.parameters = res;
	}
	if(query.defaultParameter.element === 'photogallery'){
		let q = query.pms,
			res = {};

		if(isEdit){ serviceCmd = 'editPhotogallery'; }
		if(isAdd){ serviceCmd = 'sendPhotogallery'; }

		result.command = { subCMD: serviceCmd };

		result.dataParam = 'photogallery';
		switch(query.service){
			case 'add':
				res = q;
			break;
			case 'edit':
				res = q;
			break;
		}

		result.parameters = res;
	}
	if(query.defaultParameter.element === 'selecting'){
		if(isEdit){ serviceCmd = 'editParameters'; }
		if(isAdd){ serviceCmd = 'sendParameters'; }

		result.command = { subCMD: serviceCmd };
		
		var queryConstructe = query.defaultParameter.data.split(/\s/g) || query.defaultParameter.data.split(/[;,]/);
		var queryForm = [queryConstructe[0], queryConstructe[1]];
		result.parameters = {
			dataParam: 'selecting',
			selectingData: queryForm
		};
	}
	if(query.defaultParameter.element === 'cost'){
		if(isEdit){ serviceCmd = 'editParameters'; }
		if(isAdd){ serviceCmd = 'sendParameters'; }

		result.command = { subCMD: serviceCmd };
		
		var queryForm = query.defaultParameter.data;
		result.parameters = {
			dataParam: 'cost',
			costData: queryForm
		};
	}
	if(query.defaultParameter.element === 'integer'){
		if(isEdit){ serviceCmd = 'editParameters'; }
		if(isAdd){ serviceCmd = 'sendParameters'; }

		result.command = { subCMD: serviceCmd };
		
		var queryForm = query.defaultParameter.data;
		result.parameters = {
			dataParam: 'integer',
			intData: queryForm
		};
	}
	if(query.defaultParameter.element === 'text'){
		if(isEdit){ serviceCmd = 'editParameters'; }
		if(isAdd){ serviceCmd = 'sendParameters'; }

		result.command = { subCMD: serviceCmd };
		
		var queryForm = query.defaultParameter.data;
		result.parameters = {
			dataParam: 'text',
			textData: queryForm
		};
	}

	if(isEdit){ result = { command: 1 }; }
	else{ result = { command: 0 }; }
	
	$(pm).val(result);
}

const openModal = (dataType) => {
	let queryModule = {};
	let currentFilterPage = {
		isAdd: document.location.href.indexOf("add") ? true : null,
		isEdit: document.location.href.indexOf("edit") ? true : null
	};
	
	switch(dataType){
		case 'datasets':
			let modalUI;

			if(currentFilterPage.isEdit){ modalUI = $('.edit-modals > li'); }
			if(currentFilterPage.isAdd){ modalUI = $('.add-modals > li'); }
			
			modalUI.eq(0).removeClass('modal-close');


			$('.form-rpa-addon > main #smdf-content section * input[type=file]').change(function(e){
				let formatIcon = [
					'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'
				];

				var downloadsDatasets = $(this).prop('files');
				let isMultiple = downloadsDatasets.length !== 1 ? true : null;
				var ext = "";
				var responseLoad = "";
				let dataValid = {};
				
				if(isEdit){
				
					switch(isMultiple){
						case null:

							var file = downloadsDatasets[0];
							var parts = file.name.split(".");

							if (parts.length > 1){ ext = parts.pop(); }

							if(ext === 'csv'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[0]'" />'; }
							if(ext === 'json'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[1]'" />'; }
							if(ext === 'xml'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[2]'" />'; }
							if(ext === 'xlsx'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[3]'" />'; }

							responseLoad += '\n\t<span>' + file.name + '</span>\n</li>';

							$('.downloaded-files > ul').append(responseLoad);
							
							dataValid = {service: 'edit', pms: uploadDataset(downloadedDatasets[0])};
						break;
						default:
							for(let i = 0; i < downloadedDatasets.length; i++){
								var file = downloadsDatasets[i];
								var parts = file.name.split(".");

								if (parts.length > 1){ ext = parts.pop(); }

									if(ext === 'csv'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[0]'" />'; }
									if(ext === 'json'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[1]'" />'; }
									if(ext === 'xml'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[2]'" />'; }
									if(ext === 'xlsx'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[3]'" />'; }

									responseLoad += '\n\t<span>' + file.name + '</span>\n</li>';

								}
							}

							$('.downloaded-files > ul').append(responseLoad);
							dataValid = {service: 'edit', pms: uploadMultipleDatasets(downloadedDatasets)};
						break;
					}
				}
				else{
					switch(isMultiple){
						case null:

							var file = downloadsDatasets[0];
							var parts = file.name.split(".");

							if (parts.length > 1){ ext = parts.pop(); }

							if(ext === 'csv'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[0]'" />'; }
							if(ext === 'json'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[1]'" />'; }
							if(ext === 'xml'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[2]'" />'; }
							if(ext === 'xlsx'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[3]'" />'; }

							responseLoad += '\n\t<span>' + file.name + '</span>\n</li>';

							$('.downloaded-files > ul').append(responseLoad);
							
							dataValid = {service: 'add', pms: uploadDataset(downloadedDatasets[0])};
						break;
						default:
							for(let i = 0; i < downloadedDatasets.length; i++){
								var file = downloadsDatasets[i];
								var parts = file.name.split(".");

								if (parts.length > 1){ ext = parts.pop(); }

									if(ext === 'csv'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[0]'" />'; }
									if(ext === 'json'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[1]'" />'; }
									if(ext === 'xml'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[2]'" />'; }
									if(ext === 'xlsx'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[3]'" />'; }

									responseLoad += '\n\t<span>' + file.name + '</span>\n</li>';

								}
							}

							$('.downloaded-files > ul').append(responseLoad);
							dataValid = {service: 'add', pms: uploadMultipleDatasets(downloadedDatasets)};
						break;
					}
				}
			});

			$('#header-right > img').eq(0).click(function(e,t){
				
				
				if(isEdit){
					
					if(validSmartField('dataset',dataValid) === 'editDataReady'){
						jsonQueryConstructor(dataValid, '#queryParameters');
						modalUI.eq(0).addClass('modal-close');
					}
					else{
						window.alert('Some required data for editing this attribute has not been loaded');
					}
				}
				else{
					if(validSmartField('dataset',dataValid) === 'addDataReady'){
						jsonQueryConstructor(dataValid, '#queryParameters');
						modalUI.eq(0).addClass('modal-close');
					}
					else{
						window.alert('Some required data for added this attribute has not been loaded');
					}
				}
			});

		break;
		case 'photogallery':
			let modalUI;
			let dataValid = {};

			
			if(currentFilterPage.isEdit){ modalUI = $('.edit-modals > li'); }
			if(currentFilterPage.isAdd){ modalUI = $('.add-modals > li'); }

			modalUI.eq(1).removeClass('modal-close');

			$('#header-right > img').eq(0).click(function(e,t){
				
				let constructorData;

				var checkedFormats = modalUI.eq(1).find('.form-rpa-addon > main #pg li div #format:checked'),
					photosCount = modalUI.eq(1).find('.form-rpa-addon > main #pg li div #imagesCount');


				switch(isEdit){
					case null:
						let editOut = [];
						
						for(let i = 0; i < checkedFormats.length; i++){ editOut.push(checkedFormats.eq(i).val()); }

						constructorData = {
							'value' : editOut,
							'format' : ParseInt(photosCount.val())
						}
						dataValid = {service: 'edit', pms: constructorData};
					break;
					default:
						let addOut = [];

						for(let i = 0; i < checkedFormats.length; i++){ addOut.push(checkedFormats.eq(i).val()); }

						constructorData = {
							'format' : addOut,
							'galleryCount' : ParseInt(photosCount.val())
						}
						
						dataValid = {service: 'add', pms: constructorData};
					break;
				}
				
				if(isEdit){
					if(validSmartField('photogallery',dataValid) === 'editDataReady'){
						jsonQueryConstructor(dataValid, '#queryParameters');
						modalUI.eq(1).addClass('modal-close');
					}
					else{
						window.alert('You have allowed inappropriate values for editing filters. Check them out and fix them!');
					}
				}
				else{
					if(validSmartField('photogallery',dataValid) === 'addDataReady'){
						jsonQueryConstructor(dataValid, '#queryParameters');
						modalUI.eq(1).addClass('modal-close');
					}
					else{
						window.alert('You have allowed inappropriate values for adding filters. Check them out and fix them!');
					}
				}
			});
		break;
		case 'selecting':
			var query = window.prompt('Enter two options that will be displayed in the format of radio buttons, separated by a space, semicolon or comma (the maximum number of characters in the option is 164 characters)');
			let valid = 0;
			if(query.match(/\s/g) || query.match(/[;,]/)){
				var queryForm = query.split(/\s/g) || query.split(/[;,]/);

				if(queryForm[0].match(/^[a-zA-Z0-9]\s\w+$/g)){
					valid += 1;
				}
				
				if(queryForm[1].match(/^[a-zA-Z0-9]\s\w+$/g)){
					valid += 1;
				}

				if(valid === 1){ openModal('selecting'); }
				else{ queryModule.defaultParameter = {element: 'selecting', data: query}; }
			}
			else{ openModal('selecting'); }
		break;
		case 'cost':
			var query = window.prompt('Enter a condition "<" or ">" between two costs or just enter a default cost!');
			let valid = 0;
			
			if(query.indexOf(">") || query.indexOf("<")){
				var queryForm = query.split(">") || query.split("<");

				if(queryForm[0].match(/^[0-9]+$/)){
					valid += 1;
				}
				
				if(queryForm[1].match(/^[0-9]+$/)){
					valid += 1;
				}

				if(valid === 1){ openModal('cost'); }
				else{ queryModule.defaultParameter = {element: 'cost', data: query}; }
			}
			else{
				if(query.match(/^[0-9]+$/)){ queryModule.defaultParameter = {element: 'cost', data: query}; }
				else{ openModal('cost'); }
			}
		break;
		case 'integer':
			var query = window.prompt('Enter a condition "<" or ">" between two numbers or just enter a number!');
			let valid = 0;
			
			if(query.indexOf(">") || query.indexOf("<")){
				var queryForm = query.split(">") || query.split("<");

				if(queryForm[0].match(/^[0-9]+$/)){
					valid += 1;
				}
				
				if(queryForm[1].match(/^[0-9]+$/)){
					valid += 1;
				}

				if(valid === 1){ openModal('integer'); }
				else{ queryModule.defaultParameter = {element: 'integer', data: query}; }
			}
			else{
				if(query.match(/^[0-9]+$/)){ queryModule.defaultParameter = {element: 'integer', data: query}; }
				else{ openModal('integer'); }
			}
		break;
		default:
			var query = window.prompt('Enter either a hint, or the maximum number of characters, or all together separated by commas. And it\'s better to leave the field empty;-)');

			if(query.indexOf(",")){
				var queryForm = query.split(",");
				let valid = 0;
				if(queryForm[0].match(/^[a-zA-Z0-9]\s\w+$/g)){
					valid += 1;
				}
				
				if(queryForm[1].match(/^[0-9]+$/)){
					valid += 1;
				}

				var queryRes = {
					placeholder: queryForm[0],
					maxLength: queryForm[1]
				};

				if(valid === 1){ openModal('text'); }
				else{ queryModule.defaultParameter = {element: 'integer', data: queryRes }; }
			}
			else if(query.match(/^[a-zA-Z0-9]\s\w+$/g)){
				var queryForm = {
					placeholder: query,
					maxLength: null
				};

				queryModule.defaultParameter = {element: 'integer', data: queryForm};
			}
			else if(query.match(/^[0-9]+$/)){
				var queryForm = {
					placeholder: null,
					maxLength: parseInt(query)
				};

				queryModule.defaultParameter = {element: 'integer', data: queryForm};
			}
			else{ openModal('text'); }
		break;
	}

	jsonQueryConstructor(queryModule, '#queryParameters');
}


const addFilters = (e,t) => {
	let jsonQuery = $('.add-filters > #queryParameters').val();

	var sendProccess = await fetch('/admin/api/dataServices/filters', {
		method: 'POST',
		body: {'svcQuery': jsonQuery}
	});
	
}
const editFilters = (e,t) => {
	let jsonQuery = $('.edit-filters > #queryParameters').val();

	var sendProccess = await fetch('/admin/api/dataServices/filters', {
		method: 'POST',
		body: {'svcQuery': jsonQuery}
	});
	
}
const deleteFilters = (e,t) => {
	let jsonQuery = {};

	jsonQuery = { command: 2 };

	jsonQuery.command = { subCMD: 'deleteFilters' };

	jsonQuery.parameters = {
		field: $('#filters-list > main #filter-card #header').eq($(this).index()).text()
	};

	
	var sendProccess = await fetch('/admin/api/dataServices/filters', {
		method: 'POST',
		body: {'svcQuery': jsonQuery}
	});
	
}

const uploadDataset = (dataset) => {
	//При одиночном режиме

	let queryFile = {},
		fileData = '';
	const fsAPI = new FileReader();

	fsAPI.onloadend = () => { fileData = fsAPI.result; };
	fsAPI.readAsDataURL(dataset);

	queryFile.dataConstructor.single = {
		file: fileData
	};

	return queryFile;

	
}
const uploadMultipleDatasets = (type, dsq) => {
	//При поисковом режиме

	let queryFile = {},
		fileData;
	const fsAPI = new FileReader();

	if(type === 'priority'){
		fileData = '';

		fsAPI.onloadend = () => { fileData = fsAPI.result; };
		fsAPI.readAsDataURL(dsq);

		queryFile.dataConstructor.priority = {
			smartDS: fileData
		};
	}
	if(type === 'group'){
		fileData = [];

		for(let i = 0; i < dsq.length; i++){
			fsAPI.onloadend = () => { fileData[i] = fsAPI.result; };
			fsAPI.readAsDataURL(dsq[i]);
		}

		queryFile.dataConstructor.group = {
			isSmartDS: true,
			smartDS: fileData
		};
		
	}

	return queryFile;
	
}

const validSmartField(type,data) = () => {
	if(type === 'dataset'){
		var stateValid;
		switch(data.service){
			case 'edit':
				if((data.pms.dataConstructor.priority && data.pms.dataConstructor.group) || data.pms.dataConstructor.single){
					stateValid = "editDataReady";
				}
				else{
					stateValid = "editDataError";
				}
			break;
			case 'add':
				if((data.pms.dataConstructor.priority && data.pms.dataConstructor.group) || data.pms.dataConstructor.single){
					stateValid = "addDataReady";
				}
				else{
					stateValid = "addDataError";
				}
			break;
		}

		return stateValid;
		
	}
	
	if(type === 'photogallery'){
		var stateMetaValid;
		switch(data.service){
			case 'edit':
				if(data.pms.format.length > 0 && (data.pms.galleryCount === 4 || data.pms.galleryCount > 4)){
					stateValid = "editDataReady";
				}
				else{
					stateValid = "editDataError";
				}
			break;
			case 'add':
				if(data.pms.format.length > 0 && (data.pms.galleryCount === 4 || data.pms.galleryCount > 4)){
					stateValid = "addDataReady";
				}
				else{
					stateValid = "addDataError";
				}
			break;
		}

		return stateMetaValid;
	}
}

$(document).ready(function(){
  $('.add-fields > footer button').click(AddFieldEvent);

  let elService = [$('.add-fields > footer button'),$('.edit-fields > footer button'),$('.filters-list > main #filters-card #footer nav span:nth-last-child(1)')],
	  eventService = [addFilters,updateFilters,deleteFilters];

  for(let i = 0; i < eventService.length; i++){ $(elService[i]).click(eventService[i]); }
  
});
