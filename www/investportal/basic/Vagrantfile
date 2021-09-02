require 'yaml'
require 'fileutils'

required_plugins_installed = nil
required_plugins = %w( vagrant-hostmanager vagrant-vbguest )
required_plugins.each do |plugin|
  unless Vagrant.has_plugin? plugin
    system "vagrant plugin install #{plugin}"
    required_plugins_installed = true
  end
end

# IF plugin[s] was just installed - restart required
if required_plugins_installed
  # Get CLI command[s] and call again
  system 'vagrant' + ARGV.to_s.gsub(/\[\"|\", \"|\"\]/, ' ')
  exit
end

domains = {
  app: 'yii2basic.test'
}

vagrantfile_dir_path = File.dirname(__FILE__)

config = {
  local: vagrantfile_dir_path + '/vagrant/config/vagrant-local.yml',
  example: vagrantfile_dir_path + '/vagrant/config/vagrant-local.example.yml'
}

# copy config from example if local config not exists
FileUtils.cp config[:example], config[:local] unless File.exist?(config[:local])
# read config
options = YAML.load_file config[:local]

# check github token
if options['github_token'].nil? || options['github_token'].to_s.length != 40
  puts "You must place REAL GitHub token into configuration:\n/yii2-app-basic/vagrant/config/vagrant-local.yml"
  exit
end

# vagrant configurate
Vagrant.configure(2) do |config|
  # select the box
  config.vm.box = 'bento/ubuntu-18.04'

  # should we ask about box updates?
  config.vm.box_check_update = options['box_check_update']

  config.vm.provider 'virtualbox' do |vb|
    # machine cpus count
    vb.cpus = options['cpus']
    # machine memory size
    vb.memory = options['memory']
    # machine name (for VirtualBox UI)
    vb.name = options['machine_name']
  end

  # machine name (for vagrant console)
  config.vm.define options['machine_name']

  # machine name (for guest machine console)
  config.vm.hostname = options['machine_name']

  # network settings
  config.vm.network 'private_network', ip: options['ip']

  # sync: folder 'yii2-app-advanced' (host machine) -> folder '/app' (guest machine)
  config.vm.synced_folder './', '/app', owner: 'vagrant', group: 'vagrant'

  # disable folder '/vagrant' (guest machine)
  config.vm.synced_folder '.', '/vagrant', disabled: true

  # hosts settings (host machine)
  config.vm.provision :hostmanager
  config.hostmanager.enabled            = true
  config.hostmanager.manage_host        = true
  config.hostmanager.ignore_private_ip  = false
  config.hostmanager.include_offline    = true
  config.hostmanager.aliases            = domains.values

  # quick fix for failed guest additions installations
  # config.vbguest.auto_update = false

  # provisioners
  config.vm.provision 'shell', path: './vagrant/provision/once-as-root.sh', args: [options['timezone'], options['ip']]
  config.vm.provision 'shell', path: './vagrant/provision/once-as-vagrant.sh', args: [options['github_token']], privileged: false
  config.vm.provision 'shell', path: './vagrant/provision/always-as-root.sh', run: 'always'

  # post-install message (vagrant console)
  config.vm.post_up_message = "App URL: http://#{domains[:app]}"
end
