# -*- coding: utf-8 -*-

from fabric.api import local, run, put, lcd

def test():
    """ Run Web portal tests. """
    local('npm test')
    

def build(env='dev-beta'):
    """ Build Web portal (to ./dist). """
    local('npm run build:%s' % env)
    
def deploy(dist="./dist"):
    """ Deploy Web portal. """
    with lcd(dist):
        run('rm -Rf /tmp/devportal/')
        run('mkdir /tmp/devportal')
        put("*", "/tmp/devportal/")
    run('ssh srvwww \"rm -Rf /var/www/devportal/*\"')
    run('ssh srvwww \"mkdir -p /var/www/devportal\"')
    run('scp -r /tmp/devportal/* srvwww:/var/www/devportal/')
    # run('ssh srvwww \"chown root:www-data /var/www/devportal/wp-menu/\"')
    # run('ssh srvwww \"chmod g+w /var/www/devportal/wp-menu/\"')
    # run('ssh srvwww \"rm /var/www/devportal/wp-menu/wp-menu.php\"')
    run('rm -Rf /tmp/devportal/')
