import sys
import json

topic = sys.argv[1]
key = sys.argv[2]

#Last occurence function, return index i such that p[i]==x else return -1
def lastOccur(p):
    last = []
    for i in range(0,128,1):
        last.append(-1)
    for j in range(len(p)):
        last[ord(p[j])] = j
    return last

#The Boyer Moore Algorithm, return the index, if not found return -1
def boyerMoore(t, p):
    last = lastOccur(p)
    n = len(t)
    m = len(p)

    i = j = m-1
    
    if (m>n):
        return -1
    
    if (t[i]==p[j]):
        if (j==0):
            return i
        else:
            i-=1
            j-=1
    else:
        if (ord(t[i])>127):
            i = i + m -j
            j = m - 1
        else:
            loc = last[ord(t[i])]
            i = i + m - min(j, 1+loc)
            j = m - 1

    while (i<n):
        if (t[i]==p[j]):
            if (j==0):
                return i
            else:
                i-=1
                j-=1
        else:
            if (ord(t[i])>127):
                i = i + m -j
                j = m - 1
            else:
                loc = last[ord(t[i])]
                i = i + m - min(j, 1+loc)
                j = m - 1
    
    return -1

#Twitter API + Boyer-Moore
def spamDetect(topic,keyword):
    #Setup Twitter API
    from twython import Twython
    APP_KEY = 'pVqijAxfbDRTWBadqEHs43Ozm'
    APP_SECRET = '5n0b7DDeCYCqeiHxk2vDNR6gurMcj3mEBAJQ6xIlStShY3R0DR'
    OAUTH_TOKEN = '985071710100598784-NTA548fEqcop86mOBV2bbHUOaDfz3X4'
    OAUTH_TOKEN_SECRET = 'uByR9KjsKdqLL5c1rmcUCmC3G9MNesoWwEuZ1Zf5sskLv'

    twitter = Twython(APP_KEY, APP_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET)

    #From search
    result = twitter.search(q=topic, count=30, lang='id')
    all_tweets = result['statuses']
    
    spamdata = []
    for tweet in all_tweets: 
        idx = boyerMoore(str(tweet['text']).lower(), keyword)
        if (idx!=-1):
            spamdata.append('>>>SPAM<<<')
        else:
            spamdata.append(" ")

    data = [ {
        'time': all_tweets[i]['created_at'],
        'id' : all_tweets[i]['id_str'],
        'image': all_tweets[i]['user']['profile_image_url_https'],
        'username': all_tweets[i]['user']['screen_name'],
        'name': all_tweets[i]['user']['name'],
        'text': Twython.html_for_tweet(all_tweets[i]),
        'spam': spamdata[i]
    } for i in range(len(all_tweets))]

    with open('data.txt', 'w') as f:
        json.dump(data, f)

spamDetect(topic,key.lower())