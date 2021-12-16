const tabSwitcher = () => {
  $('.admin-dashboard > header #board-tabs section').click(function(e,t){
    let allBlock = [
      $('.admin-dashboard > header #board-tabs section'),
      $('.admin-dashboard > main ul li')
    ],
        cur_allBlock = [
      $('.admin-dashboard > header #board-tabs section').eq($(this).index()),
      $('.admin-dashboard > main ul li').eq($(this).index())
    ];
    
    for(let i = 0; i < allBlock.length; i++){
      allBlock[i].removeClass('active');
      cur_allBlock[i].addClass('active');
    }
  });
}
$(document).ready(tabSwitcher);

class BasicTab extends React.Component{
  render(){
    return (
      <React.Fragment>
        <section id="dashboard-header">
          <div>
            <h2>Welcome to Admin Services!</h2>
          </div>
          <div>
            <span>Data Basic Services</span>
            <nav>
              <a href="/admin?svc=dataManagment&subSVC=attributes#add">Add object attribute</a>
              <a href="/admin?svc=dataManagment&subSVC=filters#add">Add object filter</a>
            </nav>
          </div>
          <div>
            <span>Content Basic Services</span>
            <nav>
              <a href="">Add news to portal</a>
              <a href="">Add event to portal</a>
              <a href="">Add article to content</a>
            </nav>
          </div>
        </section>
        <section id="dashboard-content">
        </section>
      </React.Fragment>
    );
  }
}
class DataTab extends React.Component{
  render(){
    
  }
}
class ContentTab extends React.Component{
  render(){
    
  }
}
let cmps = [
  <BasicTab />,<DataTab />,<ContentTab />
];

for(let i = 0; i < cmps.length; i++){
  const tabContent = document.querySelectorAll('.admin-dashboard > main ul li');
  ReactDOM.render(cmps[i], tabContent[i]);
}
